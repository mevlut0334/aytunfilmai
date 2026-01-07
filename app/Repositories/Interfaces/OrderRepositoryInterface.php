<?php

namespace App\Repositories\Interfaces;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface
{
    /**
     * Sipariş oluştur
     * Performans: Transaction içinde kullanılmalı
     *
     * @param array $data
     * @return Order
     */
    public function create(array $data): Order;

    /**
     * Sipariş kalemi oluştur
     *
     * @param int $orderId
     * @param array $itemData
     * @return void
     */
    public function createOrderItem(int $orderId, array $itemData): void;

    /**
     * ID ile sipariş bul
     *
     * @param int $id
     * @param array $relations Eager loading için ilişkiler
     * @return Order|null
     */
    public function findById(int $id, array $relations = []): ?Order;

    /**
     * Kullanıcının siparişlerini getir
     *
     * @param int $userId
     * @param array $relations Eager loading için ilişkiler
     * @return Collection
     */
    public function getUserOrders(int $userId, array $relations = []): Collection;

    /**
     * Sipariş durumunu güncelle
     *
     * @param int $orderId
     * @param string $status
     * @return bool
     */
    public function updateStatus(int $orderId, string $status): bool;

    /**
     * Tüm siparişleri getir (admin için)
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function getAll(array $filters = [], array $relations = []): Collection;

    /**
     * Tamamlanmış siparişleri getir
     *
     * @param array $relations
     * @return Collection
     */
    public function getCompletedOrders(array $relations = []): Collection;

    /**
     * Belirli tarih aralığındaki siparişleri getir
     *
     * @param string $startDate
     * @param string $endDate
     * @param array $relations
     * @return Collection
     */
    public function getOrdersByDateRange(string $startDate, string $endDate, array $relations = []): Collection;

    /**
     * Toplam gelir hesapla
     *
     * @param string|null $startDate
     * @param string|null $endDate
     * @return float
     */
    public function getTotalRevenue(?string $startDate = null, ?string $endDate = null): float;
}
