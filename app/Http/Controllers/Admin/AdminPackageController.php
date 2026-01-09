<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;

class AdminPackageController extends Controller
{
    /**
     * Paket listesi
     * Performans: Fiyata göre sıralama
     */
    public function index(): View
    {
        $packages = Package::orderBy('price')
            ->get();

        return view('admin.packages.index', compact('packages'));
    }

    /**
     * Paket oluşturma formu
     */
    public function create(): View
    {
        return view('admin.packages.create');
    }

    /**
     * Yeni paket kaydet
     */
    public function store(HttpRequest $request): RedirectResponse
{
    // Basit validation
    $validated = $request->validate([
        'name' => 'required|string|max:100',
        'description' => 'nullable|string|max:500',
        'token_amount' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0.01',
    ]);

    try {
        Package::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'token_amount' => $validated['token_amount'],
            'price' => $validated['price'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket başarıyla oluşturuldu.');

    } catch (\Exception $e) {
        \Log::error('Paket oluşturma hatası: ' . $e->getMessage());

        return back()->withInput()
            ->with('error', 'HATA: ' . $e->getMessage());
    }
}

    /**
     * Paket düzenleme formu
     */
    public function edit(int $packageId): View
    {
        $package = Package::findOrFail($packageId);
        return view('admin.packages.edit', compact('package'));
    }

    /**
     * Paketi güncelle
     */
    public function update(HttpRequest $request, int $packageId): RedirectResponse
{
    // Basit validation
    $validated = $request->validate([
        'name' => 'required|string|max:100',
        'description' => 'nullable|string|max:500',
        'token_amount' => 'required|integer|min:1',
        'price' => 'required|numeric|min:0.01',
    ]);

    try {
        $package = Package::findOrFail($packageId);

        $package->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'token_amount' => $validated['token_amount'],
            'price' => $validated['price'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.packages.index')
            ->with('success', 'Paket başarıyla güncellendi.');

    } catch (\Exception $e) {
        \Log::error('Paket güncelleme hatası: ' . $e->getMessage());

        return back()->withInput()
            ->with('error', 'HATA: ' . $e->getMessage());
    }
}
    /**
     * Paketi sil
     */
    public function destroy(int $packageId): RedirectResponse
    {
        try {
            $package = Package::findOrFail($packageId);

            // Pakete ait siparişler var mı kontrol et
            if ($package->orderItems()->exists()) {
                return back()->with('error', 'Bu pakete ait siparişler olduğu için silinemez. Bunun yerine pasif yapabilirsiniz.');
            }

            $package->delete();

            return back()->with('success', 'Paket başarıyla silindi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Paket silinirken hata oluştu: ' . $e->getMessage());
        }
    }
}
