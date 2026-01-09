<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Kullanıcı listesi (Normal kullanıcılar)
     * Performans: Pagination + eager loading + filtreleme
     */
    public function index(HttpRequest $request): View
    {
        $query = User::where('is_admin', false);

        // Filtreleme: E-posta
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        $users = $query->withCount(['orders', 'requests'])
            ->latest()
            ->paginate(20)
            ->withQueryString(); // Filtreyi pagination'da koru

        return view('admin.users.index', compact('users'));
    }

    /**
     * Kullanıcı detayı
     * Performans: Eager loading
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
                'tokenTransactions' => function ($query) {
                    $query->latest()->limit(10);
                }
            ])
            ->findOrFail($userId);

        // İstatistikler
        $stats = [
            'total_orders' => $user->orders()->count(),
            'total_spent' => $user->orders()->where('status', 'completed')->sum('final_amount'),
            'total_requests' => $user->requests()->count(),
            'pending_requests' => $user->requests()->where('status', 'pending')->count(),
            'completed_requests' => $user->requests()->where('status', 'completed')->count(),
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }
}
