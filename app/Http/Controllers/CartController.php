<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Http\Requests\ApplyCouponRequest;
use App\Services\Interfaces\CartServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    protected CartServiceInterface $cartService;

    /**
     * Dependency Injection
     */
    public function __construct(CartServiceInterface $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Sepet sayfası
     * Performans: Eager loading ile paket bilgileri
     */
    public function index(): View
    {
        $userId = Auth::id();

        // Sepet öğelerini getir
        $cartItems = $this->cartService->getUserCart($userId);

        // Kupon kodu session'dan al
        $couponCode = session('coupon_code');

        // Sepet özetini hesapla (kupon ile)
        $cartSummary = $this->cartService->getCartSummary($userId, $couponCode);

        return view('cart.index', [
            'cartItems' => $cartItems,
            'cartSummary' => $cartSummary,
            'couponCode' => $couponCode,
        ]);
    }

    /**
 * Sepete ürün ekle
 */
public function add(AddToCartRequest $request): RedirectResponse
{
    try {
        $userId = Auth::id();

        $cartItem = $this->cartService->addToCart(
            $userId,
            $request->package_id,
            $request->quantity
        );

        return back()->with('success', 'Ürün sepete eklendi.');
    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}

    /**
 * Sepet öğesi miktarını güncelle
 */
public function update(UpdateCartRequest $request, int $cartItemId): RedirectResponse
{
    try {
        $userId = Auth::id();

        $this->cartService->updateCartItemQuantity(
            $cartItemId,
            $request->quantity,
            $userId
        );

        return back()->with('success', 'Miktar güncellendi.');
    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}

    /**
     * Sepetten ürün çıkar
     */
    public function remove(int $cartItemId): RedirectResponse
    {
        try {
            $userId = Auth::id();

            $this->cartService->removeFromCart($cartItemId, $userId);

            return back()->with('success', 'Ürün sepetten çıkarıldı.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Sepeti temizle
     */
    public function clear(): RedirectResponse
    {
        try {
            $userId = Auth::id();

            $this->cartService->clearCart($userId);

            // Kupon kodunu da temizle
            session()->forget('coupon_code');

            return back()->with('success', 'Sepet temizlendi.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Kupon uygula
     */
    /**
 * Kupon uygula
 */
public function applyCoupon(ApplyCouponRequest $request): RedirectResponse
{
    try {
        $userId = Auth::id();
        $couponCode = strtoupper($request->coupon_code);

        // Sepet toplamını al
        $cartSummary = $this->cartService->getCartSummary($userId);

        // Boş sepet kontrolü
        if ($cartSummary['subtotal'] <= 0) {
            return back()->with('error', 'Sepetiniz boş.');
        }

        // Kupon özetini al
        $summary = $this->cartService->getCartSummary($userId, $couponCode);

        // Kupon geçerli mi kontrol et
        if (!$summary['coupon'] || $summary['discount'] <= 0) {
            return back()->with('error', 'Kupon geçersiz veya kullanılamıyor.');
        }

        // Kupon kodunu session'a kaydet
        session(['coupon_code' => $couponCode]);

        return back()->with('success', 'Kupon başarıyla uygulandı!');
    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}

    /**
     * Kupon kaldır
     */
    public function removeCoupon(): RedirectResponse
    {
        session()->forget('coupon_code');

        return back()->with('success', 'Kupon kaldırıldı.');
    }
}
