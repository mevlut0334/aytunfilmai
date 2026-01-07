<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\OrderServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    protected OrderServiceInterface $orderService;

    /**
     * Dependency Injection
     */
    public function __construct(OrderServiceInterface $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Sipariş listesi
     * Performans: Eager loading ile paket bilgileri
     */
    public function index(): View
    {
        $userId = Auth::id();

        $orders = $this->orderService->getUserOrders($userId);

        return view('orders.index', compact('orders'));
    }

    /**
     * Sipariş detayı
     * Performans: Eager loading + güvenlik kontrolü
     */
    public function show(int $orderId): View|RedirectResponse
    {
        $userId = Auth::id();

        $order = $this->orderService->getOrderDetails($orderId, $userId);

        if (!$order) {
            return redirect()->route('orders.index')
                ->with('error', 'Sipariş bulunamadı veya size ait değil.');
        }

        return view('orders.show', compact('order'));
    }
}
