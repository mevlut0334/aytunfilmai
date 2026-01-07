<?php

namespace App\Services;

use App\Models\CartItem;
use App\Repositories\Interfaces\CartRepositoryInterface;
use App\Repositories\Interfaces\PackageRepositoryInterface;
use App\Repositories\Interfaces\CouponRepositoryInterface;
use App\Services\Interfaces\CartServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class CartService implements CartServiceInterface
{
    protected CartRepositoryInterface $cartRepository;
    protected PackageRepositoryInterface $packageRepository;
    protected CouponRepositoryInterface $couponRepository;

    /**
     * Dependency Injection
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        PackageRepositoryInterface $packageRepository,
        CouponRepositoryInterface $couponRepository
    ) {
        $this->cartRepository = $cartRepository;
        $this->packageRepository = $packageRepository;
        $this->couponRepository = $couponRepository;
    }

    /**
     * Kullanıcının sepetini getir
     * Performans: Eager loading ile paket bilgileri
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserCart(int $userId): Collection
    {
        return $this->cartRepository->getUserCart($userId);
    }

    /**
     * Sepete ürün ekle
     *
     * @param int $userId
     * @param int $packageId
     * @param int $quantity
     * @return CartItem
     * @throws \Exception
     */
    public function addToCart(int $userId, int $packageId, int $quantity): CartItem
    {
        // Paket kontrolü
        $package = $this->packageRepository->findById($packageId);

        if (!$package) {
            throw new \Exception('Paket bulunamadı.');
        }

        // Aktif paket kontrolü
        if (!$package->isActive()) {
            throw new \Exception('Bu paket şu anda satışta değil.');
        }

        // Miktar kontrolü
        if ($quantity < 1 || $quantity > 99) {
            throw new \Exception('Miktar 1 ile 99 arasında olmalıdır.');
        }

        // Sepete ekle veya güncelle
        // Sepete ekle veya güncelle
        return $this->cartRepository->addOrUpdateItem(
            $userId,
            $packageId,
            $quantity,
            (float) $package->price  // Decimal'den float'a çevir
        );
    }

    /**
     * Sepet öğesi miktarını güncelle
     *
     * @param int $cartItemId
     * @param int $quantity
     * @param int $userId (Güvenlik için)
     * @return bool
     * @throws \Exception
     */
    public function updateCartItemQuantity(int $cartItemId, int $quantity, int $userId): bool
    {
        // Güvenlik kontrolü: Sepet öğesi kullanıcıya ait mi?
        $cartItem = CartItem::find($cartItemId);

        if (!$cartItem || $cartItem->user_id !== $userId) {
            throw new \Exception('Sepet öğesi bulunamadı veya size ait değil.');
        }

        // Miktar kontrolü
        if ($quantity < 1 || $quantity > 99) {
            throw new \Exception('Miktar 1 ile 99 arasında olmalıdır.');
        }

        return $this->cartRepository->updateQuantity($cartItemId, $quantity);
    }

    /**
     * Sepetten ürün çıkar
     *
     * @param int $cartItemId
     * @param int $userId (Güvenlik için)
     * @return bool
     * @throws \Exception
     */
    public function removeFromCart(int $cartItemId, int $userId): bool
    {
        // Güvenlik kontrolü: Sepet öğesi kullanıcıya ait mi?
        $cartItem = CartItem::find($cartItemId);

        if (!$cartItem || $cartItem->user_id !== $userId) {
            throw new \Exception('Sepet öğesi bulunamadı veya size ait değil.');
        }

        return $this->cartRepository->removeItem($cartItemId);
    }

    /**
     * Sepeti temizle
     *
     * @param int $userId
     * @return bool
     */
    public function clearCart(int $userId): bool
    {
        return $this->cartRepository->clearCart($userId);
    }

    /**
     * Sepet özeti (toplam tutar, token, kupon indirimi)
     *
     * @param int $userId
     * @param string|null $couponCode
     * @return array
     */
    public function getCartSummary(int $userId, ?string $couponCode = null): array
    {
        $cartItems = $this->cartRepository->getUserCart($userId);

        // Boş sepet kontrolü
        if ($cartItems->isEmpty()) {
            return [
                'items' => [],
                'subtotal' => 0,
                'discount' => 0,
                'total' => 0,
                'total_tokens' => 0,
                'coupon' => null,
            ];
        }

        // Ara toplam hesapla
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        // Toplam token hesapla
        $totalTokens = $cartItems->sum(function ($item) {
            return $item->quantity * $item->package->token_amount;
        });

        // Kupon kontrolü ve indirim hesaplama
        $discount = 0;
        $coupon = null;

        if ($couponCode) {
            $coupon = $this->couponRepository->findByCode($couponCode);

            if ($coupon && $coupon->isValid() && $coupon->meetsMinimumAmount($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        // Net toplam
        $total = $subtotal - $discount;

        return [
            'items' => $cartItems,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'total_tokens' => $totalTokens,
            'coupon' => $coupon,
        ];
    }

    /**
     * Sepet öğesi sayısı (badge için)
     * Performans: Basit count sorgusu
     *
     * @param int $userId
     * @return int
     */
    public function getCartItemCount(int $userId): int
    {
        return CartItem::where('user_id', $userId)->count();
    }
}
