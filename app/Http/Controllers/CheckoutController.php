<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\CartServiceInterface;
use App\Services\Interfaces\OrderServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    protected CartServiceInterface $cartService;
    protected OrderServiceInterface $orderService;

    /**
     * Dependency Injection
     */
    public function __construct(
        CartServiceInterface $cartService,
        OrderServiceInterface $orderService
    ) {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    /**
     * Ödeme sayfası
     * Performans: Sepet özetini bir kere hesapla
     */
    public function index(): View|RedirectResponse
    {
        $userId = Auth::id();

        // Sepet öğelerini kontrol et
        $cartItems = $this->cartService->getUserCart($userId);

        if ($cartItems->isEmpty()) {
            return redirect()->route('packages.index')
                ->with('error', 'Sepetiniz boş. Önce ürün ekleyin.');
        }

        // Kupon kodu session'dan al
        $couponCode = session('coupon_code');

        // Sepet özetini hesapla
        $cartSummary = $this->cartService->getCartSummary($userId, $couponCode);

        return view('checkout.index', [
            'cartSummary' => $cartSummary,
            'couponCode' => $couponCode,
        ]);
    }

    /**
     * Ödeme işlemi
     * TODO: İyzico entegrasyonu yapılacak
     * Performans: Transaction kullanımı (OrderService içinde)
     */
    public function process(Request $request): RedirectResponse
    {
        try {
            $userId = Auth::id();

            // Sepet boş mu kontrol et
            $cartItems = $this->cartService->getUserCart($userId);

            if ($cartItems->isEmpty()) {
                return redirect()->route('packages.index')
                    ->with('error', 'Sepetiniz boş.');
            }

            // Ödeme verilerini hazırla
            $paymentData = [
                'coupon_code' => session('coupon_code'),
                // TODO: İyzico için gerekli alanlar
                // 'card_holder_name' => $request->card_holder_name,
                // 'card_number' => $request->card_number,
                // 'expire_month' => $request->expire_month,
                // 'expire_year' => $request->expire_year,
                // 'cvc' => $request->cvc,
            ];

            // Sipariş oluştur ve ödeme işle
            $result = $this->orderService->createOrder($userId, $paymentData);

            $order = $result['order'];
            $paymentResult = $result['payment_result'];

            // TODO: İyzico entegrasyonu
            // Eğer 3D Secure varsa redirect_url'e yönlendir
            // if ($paymentResult['redirect_url']) {
            //     return redirect($paymentResult['redirect_url']);
            // }

            // Ödeme başarılı
            if ($paymentResult['status'] === 'success') {
                // Kupon kodunu session'dan temizle
                session()->forget('coupon_code');

                return redirect()->route('checkout.success', ['orderId' => $order->id])
                    ->with('success', 'Ödemeniz başarıyla tamamlandı!');
            }

            // Ödeme başarısız
            return redirect()->route('checkout.fail')
                ->with('error', 'Ödeme işlemi başarısız oldu.');

        } catch (\Exception $e) {
            return redirect()->route('cart.index')
                ->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Başarılı ödeme sayfası
     */
    public function success(int $orderId): View|RedirectResponse
    {
        $userId = Auth::id();

        // Sipariş detayını getir
        $order = $this->orderService->getOrderDetails($orderId, $userId);

        if (!$order) {
            return redirect()->route('user.profile')
                ->with('error', 'Sipariş bulunamadı.');
        }

        return view('checkout.success', compact('order'));
    }

    /**
     * Başarısız ödeme sayfası
     */
    public function fail(): View
    {
        return view('checkout.fail');
    }
}
