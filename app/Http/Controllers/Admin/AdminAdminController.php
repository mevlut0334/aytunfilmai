<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminAdminController extends Controller
{
    protected UserServiceInterface $userService;

    /**
     * Dependency Injection
     */
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Admin kullanıcı listesi
     * Performans: Pagination
     */
    public function index(): View
    {
        $admins = User::where('is_admin', true)
            ->latest()
            ->paginate(20);

        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Admin oluşturma formu
     */
    public function create(): View
    {
        return view('admin.admins.create');
    }

    /**
     * Yeni admin kullanıcı oluştur
     */
    public function store(HttpRequest $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Ad soyad alanı zorunludur.',
            'email.required' => 'E-posta alanı zorunludur.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.unique' => 'Bu e-posta adresi zaten kullanılıyor.',
            'phone.required' => 'Telefon alanı zorunludur.',
            'password.required' => 'Şifre alanı zorunludur.',
            'password.min' => 'Şifre en az 8 karakter olmalıdır.',
            'password.confirmed' => 'Şifreler eşleşmiyor.',
        ]);

        try {
            // Kullanıcı oluştur (is_admin = true)
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'is_admin' => true,
                'token_balance' => 0,
                'email_verified_at' => now(), // Admin'ler doğrudan aktif
            ]);

            return redirect()->route('admin.admins.index')
                ->with('success', 'Admin kullanıcı başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Admin oluşturulurken hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Admin kullanıcı sil
     */
    public function destroy(int $userId): RedirectResponse
    {
        try {
            $admin = User::where('is_admin', true)->findOrFail($userId);

            // Kendini silmeye çalışıyor mu?
            if ($admin->id === auth()->id()) {
                return back()->with('error', 'Kendi hesabınızı silemezsiniz!');
            }

            // Son admin mi kontrolü
            $adminCount = User::where('is_admin', true)->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Son admin kullanıcıyı silemezsiniz!');
            }

            $admin->delete();

            return back()->with('success', 'Admin kullanıcı başarıyla silindi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Admin silinirken hata oluştu: ' . $e->getMessage());
        }
    }
}
