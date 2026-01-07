<?php

namespace App\Repositories\Interfaces;

use App\Models\CartItem;
use Illuminate\Database\Eloquent\Collection;

interface CartRepositoryInterface
{
    /**
     * Kullanıcının sepet öğelerini getir
     * Performans: Eager loading ile paket bilgileri
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserCart(int $userId): Collection;

    /**
     * Sepete ürün ekle veya miktarı artır
     *
     * @param int $userId
     * @param int $packageId
     * @param int $quantity
     * @param float $price
     * @return CartItem
     */
    public function addOrUpdateItem(int $userId, int $packageId, int $quantity, float $price): CartItem;

    /**
     * Sepet öğesi miktarını güncelle
     *
     * @param int $cartItemId
     * @param int $quantity
     * @return bool
     */
    public function updateQuantity(int $cartItemId, int $quantity): bool;

    /**
     * Sepet öğesini sil
     *
     * @param int $cartItemId
     * @return bool
     */
    public function removeItem(int $cartItemId): bool;

    /**
     * Kullanıcının sepetini tamamen temizle
     *
     * @param int $userId
     * @return bool
     */
    public function clearCart(int $userId): bool;

    /**
     * Kullanıcının sepetindeki toplam tutar
     *
     * @param int $userId
     * @return float
     */
    public function getCartTotal(int $userId): float;

    /**
     * Kullanıcının sepetindeki toplam token
     *
     * @param int $userId
     * @return int
     */
    public function getCartTotalTokens(int $userId): int;

    /**
     * Kullanıcının sepetinde belirli paket var mı?
     *
     * @param int $userId
     * @param int $packageId
     * @return CartItem|null
     */
    public function findCartItem(int $userId, int $packageId): ?CartItem;
}
