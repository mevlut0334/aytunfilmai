<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\RequestServiceInterface;
use Illuminate\View\View;

class AdminController extends Controller
{
    protected RequestServiceInterface $requestService;

    /**
     * Dependency Injection
     */
    public function __construct(RequestServiceInterface $requestService)
    {
        $this->requestService = $requestService;
    }

    /**
     * Admin Dashboard
     * Performans: İstatistikler için eager loading
     */
    public function dashboard(): View
    {
        // İstatistikler
        $stats = [
            'total_requests' => \App\Models\Request::count(),
            'pending_requests' => \App\Models\Request::pending()->count(),
            'processing_requests' => \App\Models\Request::processing()->count(),
            'completed_requests' => \App\Models\Request::completed()->count(),
            'failed_requests' => \App\Models\Request::failed()->count(),
            'total_users' => \App\Models\User::where('is_admin', false)->count(),
        ];

        // Son 5 talep (eager loading)
        $recentRequests = \App\Models\Request::with(['user', 'processedBy'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentRequests'));
    }
}
