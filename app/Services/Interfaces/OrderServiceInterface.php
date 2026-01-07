<?php

namespace App\Services\Interfaces;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface OrderServiceInterface
{
    /**
     * Sipariş oluştur ve ödeme başlat
     * TODO: İyzico entegrasyonu yapılacak
     *
     * @param int $userId
     * @param array $paymentData
     * @return array (order, payment_result)
     */
    public function createOrder(int $userId, array $paymentData): array;

    /**
     * Ödeme sonucu işle (callback/webhook)
     * TODO: İyzico 3D Secure callback işlenecek
     *
     * @param array $callbackData
     * @return Order
     */
    public function processPaymentCallback(array $callbackData): Order;

    /**
     * Siparişi tamamla (ödeme başarılı olduğunda)
     * - Token yükle
     * - Kupon kullan
     * - Sepeti temizle
     *
     * @param int $orderId
     * @return bool
     */
    public function completeOrder(int $orderId): bool;

    /**
     * Siparişi başarısız olarak işaretle
     *
     * @param int $orderId
     * @param string $reason
     * @return bool
     */
    public function failOrder(int $orderId, string $reason): bool;

    /**
     * Kullanıcının siparişlerini getir
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserOrders(int $userId): Collection;

    /**
     * Sipariş detayını getir
     *
     * @param int $orderId
     * @param int $userId (Güvenlik için)
     * @return Order|null
     */
    public function getOrderDetails(int $orderId, int $userId): ?Order;

    /**
     * Tüm siparişleri getir (Admin)
     *
     * @param array $filters
     * @return Collection
     */
    public function getAllOrders(array $filters = []): Collection;

    /**
     * Sipariş istatistikleri (Admin)
     *
     * @param string|null $startDate
     * @param string|null $endDate
     * @return array
     */
    public function getOrderStats(?string $startDate = null, ?string $endDate = null): array;
}
