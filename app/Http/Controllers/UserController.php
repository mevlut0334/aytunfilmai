<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
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
     * Kullanıcı profil sayfası
     * Performans: Eager loading ile N+1 problemi önlenir
     */
    public function profile(): View
    {
        $user = Auth::user();

        // Eager loading ile ilişkileri getir (N+1 önleme)
        // TODO: Request modeli oluşturulunca 'requests' ilişkisi eklenecek
        $user = $this->userService->getUserById($user->id, [
            'consent',
            'tokenTransactions' => function ($query) {
                $query->latest()->limit(5); // Son 5 token işlemi
            },
            'orders' => function ($query) {
                $query->with('orderItems.package')->latest()->limit(5); // Son 5 sipariş
            }
        ]);

        return view('user.profile', compact('user'));
    }

    /**
     * Profil düzenleme sayfası
     */
    public function edit(): View
    {
        $user = Auth::user();

        return view('user.edit', compact('user'));
    }

    /**
     * Profil güncelleme
     */
    public function update(UpdateUserRequest $request): RedirectResponse
    {
        try {
            $userId = Auth::id();
            $data = $request->validated();

            // Eğer şifre boşsa, şifre alanını kaldır (güncelleme yapma)
            if (empty($data['password'])) {
                unset($data['password']);
            }

            // Kullanıcıyı güncelle
            $updated = $this->userService->updateUser($userId, $data);

            if ($updated) {
                return back()->with('success', 'Profil bilgileriniz başarıyla güncellendi.');
            }

            return back()->with('error', 'Güncelleme sırasında bir hata oluştu.');
        } catch (\Exception $e) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('error', 'Güncelleme sırasında bir hata oluştu. Lütfen tekrar deneyiniz.');
        }
    }

    /**
     * Taleplerim sayfası
     * Performans: Pagination + eager loading
     */
    public function requests(): View
    {
        $user = Auth::user();

        // Kullanıcının taleplerine eager loading ile eriş (N+1 önleme)
        $requests = $user->requests()
            ->with(['characters.images']) // Karakterler ve görseller
            ->latest()
            ->paginate(10);

        return view('user.requests', compact('requests'));
    }

    /**
     * Token işlem geçmişi
     * Performans: Pagination
     */
    public function tokenHistory(): View
    {
        $user = Auth::user();

        $transactions = $user->tokenTransactions()
            ->latest()
            ->paginate(20);

        return view('user.token-history', compact('transactions', 'user'));
    }

    /**
     * Sipariş geçmişi
     * Performans: Eager loading + pagination
     */
    public function orders(): View
    {
        $user = Auth::user();

        $orders = $user->orders()
            ->with(['orderItems.package']) // Sipariş kalemleri ve paketler (N+1 önleme)
            ->latest()
            ->paginate(10);

        return view('user.orders', compact('orders', 'user'));
    }
}
