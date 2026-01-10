<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\CartServiceInterface;
use App\Services\Interfaces\OrderServiceInterface;
use App\Services\Interfaces\IyzicoServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    protected CartServiceInterface $cartService;
    protected OrderServiceInterface $orderService;
    protected IyzicoServiceInterface $iyzicoService;

    /**
     * Dependency Injection
     */
    public function __construct(
        CartServiceInterface $cartService,
        OrderServiceInterface $orderService,
        IyzicoServiceInterface $iyzicoService
    ) {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->iyzicoService = $iyzicoService;
    }

    /**
     * Ödeme sayfası
     * Performans: Sepet özetini bir kere hesapla
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
     * Ödeme işlemi - İyzico 3D Secure
     */
    public function process(Request $request): View|RedirectResponse|Response
    {
        // Kart bilgileri validation
        $request->validate([
            'card_holder_name' => 'required|string|max:255',
            'card_number' => 'required|string|size:16',
            'expire_month' => 'required|string|size:2',
            'expire_year' => 'required|string|size:2',
            'cvc' => 'required|string|size:3',
        ], [
            'card_holder_name.required' => 'Kart üzerindeki isim zorunludur.',
            'card_number.required' => 'Kart numarası zorunludur.',
            'card_number.size' => 'Kart numarası 16 haneli olmalıdır.',
            'expire_month.required' => 'Son kullanma ayı zorunludur.',
            'expire_year.required' => 'Son kullanma yılı zorunludur.',
            'cvc.required' => 'CVC kodu zorunludur.',
            'cvc.size' => 'CVC kodu 3 haneli olmalıdır.',
        ]);

        try {
            $userId = Auth::id();
            $user = Auth::user();

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

            // Token oluştur (64 karakter, güvenli)
            $callbackToken = Str::random(64);

            $order = \App\Models\Order::create([
                'user_id' => $userId,
                'total_amount' => $cartSummary['subtotal'],
                'discount_amount' => $cartSummary['discount'],
                'final_amount' => $cartSummary['total'],
                'coupon_id' => $cartSummary['coupon_id'] ?? null,
                'status' => 'pending',
                'callback_token' => $callbackToken, // Token ekledik
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

            // İyzico verilerini hazırla
            $nameParts = explode(' ', $user->name, 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? $nameParts[0];

            $orderData = [
                'conversation_id' => 'order_' . $order->id,
                'price' => number_format($cartSummary['total'], 2, '.', ''),
                'paid_price' => number_format($cartSummary['total'], 2, '.', ''),
                'basket_id' => 'basket_' . $order->id,
                'callback_url' => route('checkout.callback', ['token' => $callbackToken]), // Token ekledik
                'buyer' => [
                    'id' => 'user_' . $user->id,
                    'name' => $firstName,
                    'surname' => $lastName,
                    'email' => $user->email,
                    'identity_number' => '11111111111', // Test için
                    'ip' => $request->ip(),
                ],
                'items' => [],
            ];

            // Sepet öğelerini ekle
            foreach ($order->orderItems as $item) {
                $orderData['items'][] = [
                    'id' => 'item_' . $item->id,
                    'name' => $item->package->name,
                    'category' => 'Token Paketi',
                    'price' => number_format($item->subtotal, 2, '.', ''),
                ];
            }

            // Kart bilgileri
            $cardData = [
                'card_holder_name' => $request->card_holder_name,
                'card_number' => $request->card_number,
                'expire_month' => $request->expire_month,
                'expire_year' => $request->expire_year,
                'cvc' => $request->cvc,
            ];

            // İyzico 3D Secure başlat
            $paymentResult = $this->iyzicoService->initiate3DSecurePayment($orderData, $cardData);

            if ($paymentResult['status'] === 'success') {
                \Log::info('3D Secure başlatıldı', [
                    'order_id' => $order->id,
                    'user_id' => $userId,
                    'token' => $callbackToken,
                    'callback_url' => $orderData['callback_url'],
                ]);

                // 3D Secure HTML sayfasını göster
                return response($paymentResult['html_content']);
            }

            // Hata durumunda siparişi iptal et
            DB::beginTransaction();
            $order->update(['status' => 'failed']);
            DB::commit();

            return redirect()->route('checkout.fail')
                ->with('error', 'Ödeme başlatılamadı: ' . ($paymentResult['error_message'] ?? 'Bilinmeyen hata'));

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Checkout process error: ' . $e->getMessage());

            return redirect()->route('cart.index')
                ->with('error', 'Bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * İyzico 3D Secure Callback
     * Token tabanlı güvenli callback işleme
     */
    public function callback(Request $request): RedirectResponse
    {
        try {
            \Log::info('Callback başladı', [
                'has_token' => $request->has('token'),
                'token_value' => $request->input('token'),
                'all_inputs' => $request->all(),
                'query_params' => $request->query(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
            ]);

            // Token'ı hem query'den hem de post'tan kontrol et
            $token = $request->input('token') ?? $request->query('token');

            if (!$token) {
                \Log::error('Callback token bulunamadı', [
                    'all_inputs' => $request->all(),
                    'query' => $request->query(),
                    'post' => $request->post(),
                ]);
                return redirect()->route('checkout.fail')
                    ->with('error', 'Geçersiz ödeme isteği - Token bulunamadı.');
            }

            \Log::info('Token bulundu', ['token' => $token]);

            // Token ile pending durumundaki order'ı bul
            $order = \App\Models\Order::where('callback_token', $token)
                ->where('status', 'pending')
                ->first();

            // Debug: Token ile tüm order'ları kontrol et
            $allOrdersWithToken = \App\Models\Order::where('callback_token', $token)->get();

            \Log::info('Order arama sonucu', [
                'token' => $token,
                'order_found' => $order ? 'yes' : 'no',
                'order_id' => $order?->id,
                'order_status' => $order?->status,
                'all_orders_with_token_count' => $allOrdersWithToken->count(),
                'all_orders_with_token' => $allOrdersWithToken->map(function($o) {
                    return [
                        'id' => $o->id,
                        'status' => $o->status,
                        'callback_token' => $o->callback_token,
                    ];
                })->toArray(),
            ]);

            if (!$order) {
                \Log::error('Order bulunamadı veya zaten işlenmiş', [
                    'token' => $token,
                    'found_orders' => $allOrdersWithToken->toArray(),
                ]);

                return redirect()->route('checkout.fail')
                    ->with('error', 'Sipariş bulunamadı veya zaten işlenmiş.');
            }

            // Kullanıcı logout olmuşsa otomatik login yap
            if (!Auth::check()) {
                Auth::loginUsingId($order->user_id);
                \Log::info('Kullanıcı otomatik login yapıldı', ['user_id' => $order->user_id]);
            }

            // Callback verilerini al
            $callbackData = [
                'conversationId' => $request->input('conversationId'),
                'paymentId' => $request->input('paymentId'),
                'conversationData' => $request->input('conversationData'),
                'status' => $request->input('status'),
                'mdStatus' => $request->input('mdStatus'),
            ];

            // 3D Secure sonucunu işle
            $result = $this->iyzicoService->handle3DSecureCallback($callbackData);

            if ($result['status'] === 'success') {
                // Ödeme başarılı - siparişi tamamla
                DB::beginTransaction();

                // Token'ı kullanılmış olarak işaretle (tek kullanımlık)
                $order->update([
                    'status' => 'completed',
                    'payment_date' => now(),
                    'transaction_id' => $result['payment_id'] ?? null,
                    'callback_token' => null, // Token'ı sil (tek kullanımlık)
                ]);

                // OrderService ile siparişi tamamla (token yükle, kupon kaydet, sepet temizle)
                $this->orderService->completeOrder($order->id);

                DB::commit();

                \Log::info('Ödeme başarılı', [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'amount' => $order->final_amount,
                ]);

                // Session'ı temizle
                session()->forget('coupon_code');

                return redirect()->route('checkout.success', ['orderId' => $order->id])
                    ->with('success', 'Ödemeniz başarıyla tamamlandı!');
            }

            // Ödeme başarısız
            DB::beginTransaction();
            $order->update([
                'status' => 'failed',
                'callback_token' => null, // Token'ı sil
            ]);
            DB::commit();

            \Log::error('Ödeme başarısız', [
                'order_id' => $order->id,
                'error' => $result['error_message'] ?? 'Bilinmeyen hata',
            ]);

            return redirect()->route('checkout.fail')
                ->with('error', 'Ödeme başarısız: ' . ($result['error_message'] ?? 'Bilinmeyen hata'));

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Checkout callback exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('checkout.fail')
                ->with('error', 'Bir hata oluştu. Lütfen destek ile iletişime geçin.');
        }
    }

    /**
     * Başarılı ödeme sayfası
     */
    public function success(int $orderId): View|RedirectResponse
    {
        $userId = Auth::id();

        // Sipariş detayını getir
        $order = $this->orderService->getOrderDetails($orderId, $userId);

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
