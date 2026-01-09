<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;

class AdminOrderController extends Controller
{
    /**
     * Sipariş listesi (E-posta filtreleme ile)
     * Performans: Eager loading + filtreleme
     */
    public function index(HttpRequest $request): View
    {
        $query = Order::with(['user', 'orderItems.package', 'coupon']);

        // Filtreleme: E-posta (3+ karakter)
        if ($request->filled('email') && strlen($request->email) >= 3) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('email', 'like', '%' . $request->email . '%');
            });
        }

        // Filtreleme: Durum
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Sipariş detayı
     * Performans: Eager loading
     */
    public function show(int $orderId): View
    {
        $order = Order::with(['user', 'orderItems.package', 'coupon'])
            ->findOrFail($orderId);

        return view('admin.orders.show', compact('order'));
    }
}
