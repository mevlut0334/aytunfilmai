<?php

namespace App\Repositories;

use App\Models\CartItem;
use App\Repositories\Interfaces\CartRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CartRepository implements CartRepositoryInterface
{
    /**
     * Kullanıcının sepet öğelerini getir
     * Performans: Eager loading ile paket bilgileri (N+1 önleme)
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserCart(int $userId): Collection
    {
        return CartItem::with('package')
            ->where('user_id', $userId)
            ->get();
    }

    /**
     * Sepete ürün ekle veya miktarı artır
     * Performans: firstOrCreate ile tek sorgu
     *
     * @param int $userId
     * @param int $packageId
     * @param int $quantity
     * @param float $price
     * @return CartItem
     */
    public function addOrUpdateItem(int $userId, int $packageId, int $quantity, float $price): CartItem
    {
        $cartItem = $this->findCartItem($userId, $packageId);

        if ($cartItem) {
            // Mevcut öğeyi güncelle
            $cartItem->update([
                'quantity' => $cartItem->quantity + $quantity,
                'price' => $price, // Güncel fiyatı kaydet
            ]);
            return $cartItem->fresh();
        }

        // Yeni öğe oluştur
        return CartItem::create([
            'user_id' => $userId,
            'package_id' => $packageId,
            'quantity' => $quantity,
            'price' => $price,
        ]);
    }

    /**
     * Sepet öğesi miktarını güncelle
     *
     * @param int $cartItemId
     * @param int $quantity
     * @return bool
     */
    public function updateQuantity(int $cartItemId, int $quantity): bool
    {
        $cartItem = CartItem::find($cartItemId);

        if (!$cartItem) {
            return false;
        }

        return $cartItem->update(['quantity' => $quantity]);
    }

    /**
     * Sepet öğesini sil
     *
     * @param int $cartItemId
     * @return bool
     */
    public function removeItem(int $cartItemId): bool
    {
        $cartItem = CartItem::find($cartItemId);

        if (!$cartItem) {
            return false;
        }

        return $cartItem->delete();
    }

    /**
     * Kullanıcının sepetini tamamen temizle
     *
     * @param int $userId
     * @return bool
     */
    public function clearCart(int $userId): bool
    {
        return CartItem::where('user_id', $userId)->delete() > 0;
    }

    /**
     * Kullanıcının sepetindeki toplam tutar
     * Performans: DB'de hesaplama
     *
     * @param int $userId
     * @return float
     */
    public function getCartTotal(int $userId): float
    {
        return CartItem::where('user_id', $userId)
            ->selectRaw('SUM(quantity * price) as total')
            ->value('total') ?? 0;
    }

    /**
     * Kullanıcının sepetindeki toplam token
     * Performans: Eager loading + collection işlemi
     *
     * @param int $userId
     * @return int
     */
    public function getCartTotalTokens(int $userId): int
    {
        $cartItems = $this->getUserCart($userId);

        return $cartItems->sum(function ($item) {
            return $item->quantity * $item->package->token_amount;
        });
    }

    /**
     * Kullanıcının sepetinde belirli paket var mı?
     *
     * @param int $userId
     * @param int $packageId
     * @return CartItem|null
     */
    public function findCartItem(int $userId, int $packageId): ?CartItem
    {
        return CartItem::where('user_id', $userId)
            ->where('package_id', $packageId)
            ->first();
    }
}
