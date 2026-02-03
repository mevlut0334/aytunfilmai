<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Services\Interfaces\OrderServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AdminOrderController extends Controller
{
    protected OrderServiceInterface $orderService;

    public function __construct(OrderServiceInterface $orderService)
    {
        $this->orderService = $orderService;
    }

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

    /**
     * Sipariş onaylama - Havale/EFT onayı
     */
    public function approve(int $orderId): RedirectResponse
    {
        try {
            $order = Order::findOrFail($orderId);

            // Zaten tamamlanmış siparişi tekrar onaylama
            if ($order->status === 'completed') {
                return redirect()->back()
                    ->with('error', 'Bu sipariş zaten tamamlanmış.');
            }

            // Başarısız siparişi onaylama
            if ($order->status === 'failed') {
                return redirect()->back()
                    ->with('error', 'Başarısız sipariş onaylanamaz.');
            }

            DB::beginTransaction();

            // Siparişi tamamla
            $order->update([
                'status' => 'completed',
                'payment_date' => now(),
            ]);

            // OrderService ile siparişi tamamla (token yükle, kupon kaydet)
            $this->orderService->completeOrder($order->id);

            DB::commit();

            \Log::info('Sipariş onaylandı', [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'admin_id' => auth()->id(),
                'amount' => $order->final_amount,
            ]);

            return redirect()->back()
                ->with('success', 'Sipariş onaylandı! Tokenlar kullanıcıya yüklendi.');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Sipariş onaylama hatası', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }
}
