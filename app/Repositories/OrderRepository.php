<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    /**
     * Sipariş oluştur
     * Performans: Transaction içinde kullanılmalı
     *
     * @param array $data
     * @return Order
     */
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    /**
     * Sipariş kalemi oluştur
     *
     * @param int $orderId
     * @param array $itemData
     * @return void
     */
    public function createOrderItem(int $orderId, array $itemData): void
    {
        OrderItem::create(array_merge($itemData, ['order_id' => $orderId]));
    }

    /**
     * ID ile sipariş bul
     * Performans: Eager loading desteği
     *
     * @param int $id
     * @param array $relations
     * @return Order|null
     */
    public function findById(int $id, array $relations = []): ?Order
    {
        $query = Order::query();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->find($id);
    }

    /**
     * Kullanıcının siparişlerini getir
     * Performans: Eager loading + latest sıralama
     *
     * @param int $userId
     * @param array $relations
     * @return Collection
     */
    public function getUserOrders(int $userId, array $relations = []): Collection
    {
        $query = Order::where('user_id', $userId)->latest();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->get();
    }

    /**
     * Sipariş durumunu güncelle
     *
     * @param int $orderId
     * @param string $status
     * @return bool
     */
    public function updateStatus(int $orderId, string $status): bool
    {
        $order = Order::find($orderId);

        if (!$order) {
            return false;
        }

        return $order->update(['status' => $status]);
    }

    /**
     * Tüm siparişleri getir (admin için)
     * Performans: Filtreleme + eager loading
     *
     * @param array $filters
     * @param array $relations
     * @return Collection
     */
    public function getAll(array $filters = [], array $relations = []): Collection
    {
        $query = Order::query();

        // Durum filtresi
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Kullanıcı filtresi
        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        // Tarih aralığı filtresi
        if (isset($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        // Eager loading
        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->latest()->get();
    }

    /**
     * Tamamlanmış siparişleri getir
     * Performans: Scope kullanımı + eager loading
     *
     * @param array $relations
     * @return Collection
     */
    public function getCompletedOrders(array $relations = []): Collection
    {
        $query = Order::completed();

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->latest()->get();
    }

    /**
     * Belirli tarih aralığındaki siparişleri getir
     *
     * @param string $startDate
     * @param string $endDate
     * @param array $relations
     * @return Collection
     */
    public function getOrdersByDateRange(string $startDate, string $endDate, array $relations = []): Collection
    {
        $query = Order::whereBetween('created_at', [$startDate, $endDate]);

        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query->latest()->get();
    }

    /**
     * Toplam gelir hesapla
     * Performans: DB'de hesaplama
     *
     * @param string|null $startDate
     * @param string|null $endDate
     * @return float
     */
    public function getTotalRevenue(?string $startDate = null, ?string $endDate = null): float
    {
        $query = Order::where('status', 'completed');

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return $query->sum('final_amount') ?? 0;
    }
}
