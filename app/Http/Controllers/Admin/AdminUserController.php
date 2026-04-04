<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminUserController extends Controller
{
    protected UserServiceInterface $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Kullanıcı listesi (Normal kullanıcılar)
     */
    public function index(HttpRequest $request): View
    {
        $query = User::where('is_admin', false);

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        $users = $query->withCount(['orders', 'requests'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Kullanıcı detayı
     */
    public function show(int $userId): View
    {
        $user = User::where('is_admin', false)
            ->with([
                'orders' => function ($query) {
                    $query->latest()->limit(5);
                },
                'requests' => function ($query) {
                    $query->latest()->limit(5);
                },
            ])
            ->findOrFail($userId);

        $stats = [
            'total_orders'       => $user->orders()->count(),
            'total_spent'        => $user->orders()->where('status', 'completed')->sum('final_amount'),
            'total_requests'     => $user->requests()->count(),
            'pending_requests'   => $user->requests()->where('status', 'pending')->count(),
            'completed_requests' => $user->requests()->where('status', 'completed')->count(),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }

    /**
     * Kullanıcı şifresini güncelleme (Admin)
     */
    public function updatePassword(HttpRequest $request, int $userId): RedirectResponse
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'new_password.required'  => 'Yeni şifre alanı zorunludur.',
            'new_password.min'       => 'Şifre en az 8 karakter olmalıdır.',
            'new_password.confirmed' => 'Şifre onayı eşleşmiyor.',
        ]);

        try {
            $user = User::where('is_admin', false)->findOrFail($userId);
            $user->password = Hash::make($request->new_password);
            $user->save();

            return back()->with('success', 'Kullanıcının şifresi başarıyla güncellendi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Şifre güncellenirken bir hata oluştu.');
        }
    }

    /**
     * Kullanıcıya token ekle (Admin)
     */
    public function addTokens(HttpRequest $request, int $userId): RedirectResponse
    {
        $request->validate([
            'token_amount'       => 'required|integer|min:1',
            'token_description'  => 'required|string|max:255',
        ], [
            'token_amount.required'      => 'Token miktarı zorunludur.',
            'token_amount.min'           => 'Token miktarı en az 1 olmalıdır.',
            'token_description.required' => 'Açıklama zorunludur.',
        ]);

        try {
            $user = User::where('is_admin', false)->findOrFail($userId);

            $this->userService->addTokens(
                $user->id,
                (int) $request->token_amount,
                $request->token_description
            );

            return back()->with('success', $request->token_amount . ' token başarıyla eklendi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Token eklenirken bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Kullanıcı sil (Admin)
     * Kullanıcının tüm görselleri ve ilişkili verileri de silinir.
     */
    public function destroy(int $userId): RedirectResponse
    {
        try {
            $user = User::where('is_admin', false)->findOrFail($userId);

            // Kullanıcıya ait request görsellerini storage'dan sil
            foreach ($user->requests as $filmRequest) {
                Storage::disk('public')->deleteDirectory("requests/{$filmRequest->id}");
            }

            $this->userService->deleteUser($userId);

            return redirect()->route('admin.users.index')
                ->with('success', 'Kullanıcı ve tüm verileri başarıyla silindi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Kullanıcı silinirken bir hata oluştu: ' . $e->getMessage());
        }
    }
}
