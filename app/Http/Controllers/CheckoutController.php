<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\CartServiceInterface;
use App\Services\Interfaces\OrderServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    protected CartServiceInterface $cartService;
    protected OrderServiceInterface $orderService;

    /**
     * Dependency Injection
     */
    public function __construct(
        CartServiceInterface $cartService,
        OrderServiceInterface $orderService
    ) {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    /**
     * Ödeme sayfası
     */
    public function index(): View|RedirectResponse
    {
        $userId = Auth::id();

        // Sepet öğelerini kontrol et
        $cartItems = $this->cartService->getUserCart($userId);

        if ($cartItems->isEmpty()) {
            return redirect()->route('packages.index')
                ->with('error', 'Sepetiniz boş. Önce ürün ekleyin.');
        }

        // Kupon kodu session'dan al
        $couponCode = session('coupon_code');

        // Sepet özetini hesapla
        $cartSummary = $this->cartService->getCartSummary($userId, $couponCode);

        return view('checkout.index', [
            'cartSummary' => $cartSummary,
            'couponCode' => $couponCode,
        ]);
    }

    /**
     * Havale/EFT ile ödeme işlemi
     */
    public function process(Request $request): RedirectResponse
    {
        // Form validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|min:10|max:15',
            'terms' => 'required|accepted',
        ], [
            'name.required' => 'Ad Soyad zorunludur.',
            'email.required' => 'E-posta zorunludur.',
            'phone.required' => 'Telefon numarası zorunludur.',
            'phone.min' => 'Telefon numarası en az 10 haneli olmalıdır.',
            'terms.required' => 'Kullanım koşullarını kabul etmelisiniz.',
        ]);

        try {
            $userId = Auth::id();

            // Sepet boş mu kontrol et
            $cartItems = $this->cartService->getUserCart($userId);

            if ($cartItems->isEmpty()) {
                return redirect()->route('packages.index')
                    ->with('error', 'Sepetiniz boş.');
            }

            // Kupon kodu
            $couponCode = session('coupon_code');

            // Sepet özetini hesapla
            $cartSummary = $this->cartService->getCartSummary($userId, $couponCode);

            // Sipariş oluştur (pending durumunda)
            DB::beginTransaction();

            $order = \App\Models\Order::create([
                'user_id' => $userId,
                'total_amount' => $cartSummary['subtotal'],
                'discount_amount' => $cartSummary['discount'],
                'final_amount' => $cartSummary['total'],
                'coupon_id' => $cartSummary['coupon_id'] ?? null,
                'status' => 'pending', // Havale bekliyor
            ]);

            // Sipariş kalemlerini oluştur
            foreach ($cartItems as $cartItem) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'package_id' => $cartItem->package_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->package->price,
                    'subtotal' => $cartItem->quantity * $cartItem->package->price,
                ]);
            }

            DB::commit();

            \Log::info('Havale siparişi oluşturuldu', [
                'order_id' => $order->id,
                'user_id' => $userId,
                'amount' => $order->final_amount,
            ]);

            // Session'ı temizle
            session()->forget('coupon_code');

            // Sepeti temizle
            $this->cartService->clearCart($userId);

            // Başarı sayfasına yönlendir
            return redirect()->route('checkout.success', ['orderId' => $order->id])
                ->with('success', 'Siparişiniz oluşturuldu. Lütfen havale bilgilerini kontrol edin.');

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Checkout process error: ' . $e->getMessage());

            return redirect()->route('cart.index')
                ->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Başarılı ödeme sayfası - Havale bilgileri göster
     */
    public function success(int $orderId): View|RedirectResponse
    {
        $userId = Auth::id();

        // Sipariş detayını getir
        $order = \App\Models\Order::with('orderItems.package')
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->first();

        if (!$order) {
            return redirect()->route('user.profile')
                ->with('error', 'Sipariş bulunamadı.');
        }

        return view('checkout.success', compact('order'));
    }

    /**
     * Başarısız ödeme sayfası
     */
    public function fail(): View
    {
        return view('checkout.fail');
    }
}
