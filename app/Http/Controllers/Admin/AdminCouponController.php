<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;

class AdminCouponController extends Controller
{
    /**
     * Kupon listesi
     */
    public function index(): View
    {
        $coupons = Coupon::withCount('usages')
            ->latest()
            ->get();

        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Kupon oluşturma formu
     */
    public function create(): View
    {
        return view('admin.coupons.create');
    }

    /**
     * Yeni kupon kaydet
     */
    public function store(HttpRequest $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0.01',
            'min_amount' => 'nullable|numeric|min:0',
            'max_usage' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        try {
            Coupon::create([
                'code' => strtoupper($validated['code']),
                'type' => $validated['type'],
                'discount_value' => $validated['discount_value'],
                'min_amount' => $validated['min_amount'] ?? 0,
                'max_usage' => $validated['max_usage'],
                'usage_count' => 0,
                'starts_at' => $validated['starts_at'],
                'expires_at' => $validated['expires_at'],
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Kupon başarıyla oluşturuldu.');

        } catch (\Exception $e) {
            \Log::error('Kupon oluşturma hatası: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'HATA: ' . $e->getMessage());
        }
    }

    /**
     * Kupon düzenleme formu
     */
    public function edit(int $couponId): View
    {
        $coupon = Coupon::findOrFail($couponId);
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Kuponu güncelle
     */
    public function update(HttpRequest $request, int $couponId): RedirectResponse
    {
        $coupon = Coupon::findOrFail($couponId);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $couponId,
            'type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0.01',
            'min_amount' => 'nullable|numeric|min:0',
            'max_usage' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        try {
            $coupon->update([
                'code' => strtoupper($validated['code']),
                'type' => $validated['type'],
                'discount_value' => $validated['discount_value'],
                'min_amount' => $validated['min_amount'] ?? 0,
                'max_usage' => $validated['max_usage'],
                'starts_at' => $validated['starts_at'],
                'expires_at' => $validated['expires_at'],
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()->route('admin.coupons.index')
                ->with('success', 'Kupon başarıyla güncellendi.');

        } catch (\Exception $e) {
            \Log::error('Kupon güncelleme hatası: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', 'HATA: ' . $e->getMessage());
        }
    }

    /**
     * Kuponu sil
     */
    public function destroy(int $couponId): RedirectResponse
    {
        try {
            $coupon = Coupon::findOrFail($couponId);

            // Kupona ait kullanımlar var mı kontrol et
            if ($coupon->usages()->exists()) {
                return back()->with('error', 'Bu kupona ait kullanımlar olduğu için silinemez. Bunun yerine pasif yapabilirsiniz.');
            }

            $coupon->delete();

            return back()->with('success', 'Kupon başarıyla silindi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Kupon silinirken hata oluştu: ' . $e->getMessage());
        }
    }
}
