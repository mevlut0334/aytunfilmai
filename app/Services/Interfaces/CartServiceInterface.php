<?php

namespace App\Services\Interfaces;

use App\Models\CartItem;
use Illuminate\Database\Eloquent\Collection;

interface CartServiceInterface
{
    /**
     * Kullanıcının sepetini getir
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserCart(int $userId): Collection;

    /**
     * Sepete ürün ekle
     *
     * @param int $userId
     * @param int $packageId
     * @param int $quantity
     * @return CartItem
     */
    public function addToCart(int $userId, int $packageId, int $quantity): CartItem;

    /**
     * Sepet öğesi miktarını güncelle
     *
     * @param int $cartItemId
     * @param int $quantity
     * @param int $userId (Güvenlik için)
     * @return bool
     */
    public function updateCartItemQuantity(int $cartItemId, int $quantity, int $userId): bool;

    /**
     * Sepetten ürün çıkar
     *
     * @param int $cartItemId
     * @param int $userId (Güvenlik için)
     * @return bool
     */
    public function removeFromCart(int $cartItemId, int $userId): bool;

    /**
     * Sepeti temizle
     *
     * @param int $userId
     * @return bool
     */
    public function clearCart(int $userId): bool;

    /**
     * Sepet özeti (toplam tutar, token, vb.)
     *
     * @param int $userId
     * @param string|null $couponCode
     * @return array
     */
    public function getCartSummary(int $userId, ?string $couponCode = null): array;

    /**
     * Sepet öğesi sayısı (badge için)
     *
     * @param int $userId
     * @return int
     */
    public function getCartItemCount(int $userId): int;
}
