<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
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
     * Kayıt sayfasını göster
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * Kayıt işlemi
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        try {
            // Kullanıcı kaydet (onaylar ile birlikte)
            $user = $this->userService->register($request->validated());

            // Otomatik giriş yap
            Auth::login($user);

            // Başarı mesajı
            return redirect()->route('user.profile')
                ->with('success', 'Kayıt işlemi başarılı! Hoş geldiniz.');
        } catch (\Exception $e) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Kayıt sırasında bir hata oluştu. Lütfen tekrar deneyiniz.');
        }
    }

    /**
     * Giriş sayfasını göster
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Giriş işlemi
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Giriş denemesi
        $user = $this->userService->login($credentials['email'], $credentials['password']);

        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'E-posta veya şifre hatalı.');
        }

        // Remember me
        if ($remember) {
            Auth::login($user, true);
        }

        // Yönlendirme: Admin ise admin paneli, normal kullanıcı ise profil sayfası
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Hoş geldiniz, ' . $user->name);
        }

        return redirect()->route('user.profile')
            ->with('success', 'Hoş geldiniz, ' . $user->name);
    }

    /**
     * Çıkış işlemi
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Başarıyla çıkış yaptınız.');
    }
}
