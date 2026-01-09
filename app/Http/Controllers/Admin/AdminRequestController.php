<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\RequestServiceInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;

class AdminRequestController extends Controller
{
    protected RequestServiceInterface $requestService;
    protected UserServiceInterface $userService;

    /**
     * Dependency Injection
     */
    public function __construct(
        RequestServiceInterface $requestService,
        UserServiceInterface $userService
    ) {
        $this->requestService = $requestService;
        $this->userService = $userService;
    }

    /**
     * Talep listesi (Filtreleme ile)
     * Performans: Eager loading + query builder
     */
    public function index(HttpRequest $request): View
    {
        // Base query
        $query = \App\Models\Request::with(['user', 'processedBy', 'characters']);

        // Filtreleme: Başlık (3+ karakter)
        if ($request->filled('title') && strlen($request->title) >= 3) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // Filtreleme: Kullanıcı
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filtreleme: Durum
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtreleme: İşleyen Admin
        if ($request->filled('processed_by')) {
            $query->where('processed_by', $request->processed_by);
        }

        // Sıralama
        $requests = $query->latest()->paginate(20)->withQueryString();

        // Filtreleme için kullanıcı ve admin listesi
        $users = \App\Models\User::where('is_admin', false)->orderBy('name')->get();
        $admins = \App\Models\User::where('is_admin', true)->orderBy('name')->get();

        return view('admin.requests.index', compact('requests', 'users', 'admins'));
    }

    /**
     * Talep detayı
     * Performans: Eager loading
     */
    public function show(int $requestId): View
    {
        $request = \App\Models\Request::with([
            'user',
            'processedBy',
            'characters.images' => function ($query) {
                $query->ordered();
            }
        ])->findOrFail($requestId);

        return view('admin.requests.show', compact('request'));
    }

    /**
     * Talep durumunu güncelle
     * - processing: admin_id kaydet
     * - completed: video_url + token düş
     * - failed: error_message kaydet
     */
    public function updateStatus(HttpRequest $request, int $requestId): RedirectResponse
    {
        try {
            $filmRequest = \App\Models\Request::findOrFail($requestId);

            $status = $request->input('status');
            $adminId = auth()->id();
            $additionalData = [];

            // Completed: Video URL + Token Düşme
            if ($status === 'completed') {
                $request->validate([
                    'video_url' => 'required|url|max:500',
                    'token_amount' => 'required|integer|min:1',
                ]);

                $additionalData['video_url'] = $request->input('video_url');
                $tokenAmount = (int) $request->input('token_amount');

                // Kullanıcıdan token düş
                if (!$filmRequest->user->hasEnoughTokens($tokenAmount)) {
                    return back()->with('error', 'Kullanıcının yeterli token bakiyesi yok!');
                }

                $this->userService->deductTokens(
                    $filmRequest->user_id,
                    $tokenAmount,
                    "Film talebi tamamlandı - {$filmRequest->title}"
                );
            }

            // Failed: Hata Mesajı
            if ($status === 'failed') {
                $request->validate([
                    'error_message' => 'required|string|max:1000',
                ]);

                $additionalData['error_message'] = $request->input('error_message');
            }

            // Durumu güncelle (admin ID ile)
            $this->requestService->updateRequestStatus($requestId, $status, $adminId, $additionalData);

            return back()->with('success', 'Talep durumu başarıyla güncellendi.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
