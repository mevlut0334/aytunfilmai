<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\CartRepositoryInterface;
use App\Repositories\Interfaces\CouponRepositoryInterface;
use App\Services\Interfaces\OrderServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderService implements OrderServiceInterface
{
    protected OrderRepositoryInterface $orderRepository;
    protected CartRepositoryInterface $cartRepository;
    protected CouponRepositoryInterface $couponRepository;
    protected UserServiceInterface $userService;

    /**
     * Dependency Injection
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CartRepositoryInterface $cartRepository,
        CouponRepositoryInterface $couponRepository,
        UserServiceInterface $userService
    ) {
        $this->orderRepository = $orderRepository;
        $this->cartRepository = $cartRepository;
        $this->couponRepository = $couponRepository;
        $this->userService = $userService;
    }

    /**
     * Sipariş oluştur ve ödeme başlat
     * TODO: İyzico entegrasyonu yapılacak
     * Performans: Transaction kullanımı
     *
     * @param int $userId
     * @param array $paymentData
     * @return array
     * @throws \Exception
     */
    public function createOrder(int $userId, array $paymentData): array
    {
        return DB::transaction(function () use ($userId, $paymentData) {
            // Sepeti kontrol et
            $cartItems = $this->cartRepository->getUserCart($userId);

            if ($cartItems->isEmpty()) {
                throw new \Exception('Sepetiniz boş.');
            }

            // Sepet toplamını hesapla
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->price;
            });

            // Kupon kontrolü
            $discount = 0;
            $couponId = null;

            if (isset($paymentData['coupon_code'])) {
                $coupon = $this->couponRepository->findByCode($paymentData['coupon_code']);

                if ($coupon && $coupon->isValid() && $coupon->meetsMinimumAmount($subtotal)) {
                    $discount = $coupon->calculateDiscount($subtotal);
                    $couponId = $coupon->id;
                } else {
                    throw new \Exception('Kupon geçersiz veya minimum tutar karşılanmıyor.');
                }
            }

            $finalAmount = $subtotal - $discount;

            // Sipariş oluştur
            $order = $this->orderRepository->create([
                'user_id' => $userId,
                'total_amount' => $subtotal,
                'discount_amount' => $discount,
                'final_amount' => $finalAmount,
                'coupon_id' => $couponId,
                'status' => 'pending',
                'payment_date' => null,
                'transaction_id' => null,
            ]);

            // Sipariş kalemlerini oluştur
            foreach ($cartItems as $cartItem) {
                $this->orderRepository->createOrderItem($order->id, [
                    'package_id' => $cartItem->package_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->price,
                    'subtotal' => $cartItem->quantity * $cartItem->price,
                ]);
            }

            // TODO: İYZİCO ÖDEME ENTEGRASYONU BURAYA GELECEK
            // Şimdilik fake ödeme başarılı kabul ediyoruz (test için)
            // Gerçek entegrasyonda:
            // 1. İyzico API'ye istek at
            // 2. 3D Secure sayfasına yönlendir
            // 3. Callback'i bekle
            // 4. Ödeme sonucunu işle

            $paymentResult = [
                'status' => 'success', // TODO: İyzico'dan gelecek
                'transaction_id' => 'FAKE_' . time(), // TODO: İyzico'dan gelecek
                'redirect_url' => null, // TODO: 3D Secure URL'i gelecek
            ];

            // FAKE ÖDEME İÇİN: Siparişi hemen tamamla
            if ($paymentResult['status'] === 'success') {
                $order->update([
                    'status' => 'completed',
                    'payment_date' => now(),
                    'transaction_id' => $paymentResult['transaction_id'],
                ]);

                $this->completeOrder($order->id);
            }

            return [
                'order' => $order->fresh(['orderItems.package']),
                'payment_result' => $paymentResult,
            ];
        });
    }

    /**
     * Ödeme sonucu işle (callback/webhook)
     * TODO: İyzico 3D Secure callback işlenecek
     *
     * @param array $callbackData
     * @return Order
     * @throws \Exception
     */
    public function processPaymentCallback(array $callbackData): Order
    {
        // TODO: İyzico callback verilerini parse et
        // TODO: İyzico'ya doğrulama isteği at
        // TODO: Ödeme durumunu kontrol et

        throw new \Exception('İyzico entegrasyonu henüz yapılmadı.');
    }

    /**
     * Siparişi tamamla (ödeme başarılı olduğunda)
     * - Token yükle
     * - Kupon kullan
     * - Sepeti temizle
     * Performans: Transaction kullanımı
     *
     * @param int $orderId
     * @return bool
     * @throws \Exception
     */
    public function completeOrder(int $orderId): bool
    {
        return DB::transaction(function () use ($orderId) {
            $order = $this->orderRepository->findById($orderId, ['orderItems.package', 'coupon', 'user']);

            if (!$order) {
                throw new \Exception('Sipariş bulunamadı.');
            }

            // TODO: Bu kontrolü kaldırdık çünkü createOrder içinde zaten completed yapıyoruz
            // Zaten tamamlanmışsa
            // if ($order->isCompleted()) {
            //     return true;
            // }

            // Toplam token hesapla
            $totalTokens = $order->orderItems->sum(function ($item) {
                return $item->quantity * $item->package->token_amount;
            });

            // Token yükle (her paket için ayrı transaction kaydı)
            foreach ($order->orderItems as $item) {
                $tokenAmount = $item->quantity * $item->package->token_amount;
                $description = "Paket satın alma - {$item->package->name} x {$item->quantity}";

                $this->userService->addTokens($order->user_id, $tokenAmount, $description);
            }

            // Kupon kullanıldıysa kaydet
            if ($order->coupon_id) {
                $this->couponRepository->incrementUsage($order->coupon_id);

                $this->couponRepository->createUsage([
                    'coupon_id' => $order->coupon_id,
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'discount_amount' => $order->discount_amount,
                    'used_at' => now(),
                ]);
            }

            // Sepeti temizle
            $this->cartRepository->clearCart($order->user_id);

            // Sipariş durumunu güncelle
            $this->orderRepository->updateStatus($orderId, 'completed');

            return true;
        });
    }

    /**
     * Siparişi başarısız olarak işaretle
     *
     * @param int $orderId
     * @param string $reason
     * @return bool
     */
    public function failOrder(int $orderId, string $reason): bool
    {
        // TODO: Başarısızlık nedenini loglayabilirsiniz
        return $this->orderRepository->updateStatus($orderId, 'failed');
    }

    /**
     * Kullanıcının siparişlerini getir
     * Performans: Eager loading
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserOrders(int $userId): Collection
    {
        return $this->orderRepository->getUserOrders($userId, [
            'orderItems.package',
            'coupon',
        ]);
    }

    /**
     * Sipariş detayını getir
     * Performans: Eager loading + güvenlik kontrolü
     *
     * @param int $orderId
     * @param int $userId (Güvenlik için)
     * @return Order|null
     */
    public function getOrderDetails(int $orderId, int $userId): ?Order
    {
        $order = $this->orderRepository->findById($orderId, [
            'orderItems.package',
            'coupon',
            'user',
        ]);

        // Güvenlik kontrolü: Sipariş kullanıcıya ait mi?
        if (!$order || $order->user_id !== $userId) {
            return null;
        }

        return $order;
    }

    /**
     * Tüm siparişleri getir (Admin)
     * Performans: Filtreleme + eager loading
     *
     * @param array $filters
     * @return Collection
     */
    public function getAllOrders(array $filters = []): Collection
    {
        return $this->orderRepository->getAll($filters, [
            'user',
            'orderItems.package',
            'coupon',
        ]);
    }

    /**
     * Sipariş istatistikleri (Admin)
     *
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public function getOrderStats(?string $startDate = null, ?string $endDate = null): array
    {
        $filters = [];

        if ($startDate) {
            $filters['start_date'] = $startDate;
        }

        if ($endDate) {
            $filters['end_date'] = $endDate;
        }

        $orders = $this->orderRepository->getAll($filters);
        $completedOrders = $orders->where('status', 'completed');

        $totalRevenue = $this->orderRepository->getTotalRevenue($startDate, $endDate);

        return [
            'total_orders' => $orders->count(),
            'completed_orders' => $completedOrders->count(),
            'pending_orders' => $orders->where('status', 'pending')->count(),
            'failed_orders' => $orders->where('status', 'failed')->count(),
            'total_revenue' => $totalRevenue,
            'average_order_value' => $completedOrders->count() > 0
                ? $totalRevenue / $completedOrders->count()
                : 0,
        ];
    }
}
