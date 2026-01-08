<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\RequestServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Request as HttpRequest;
use App\Http\Requests\CreateRequestRequest;

class RequestController extends Controller
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
     * Talep listesi
     * Performans: Eager loading
     */
    public function index(): View
    {
        $userId = Auth::id();

        $requests = $this->requestService->getUserRequests($userId);

        return view('requests.index', compact('requests'));
    }

    /**
     * Talep oluşturma formu
     */
    public function create(): View
    {
        return view('requests.create');
    }

    /**
     * Talep kaydetme
     * Form Request ile validation yapılacak
     */
    public function store(CreateRequestRequest $request): RedirectResponse
{
    try {
        $userId = Auth::id();

        $filmRequest = $this->requestService->createRequest($userId, $request->all());

        return redirect()->route('requests.show', $filmRequest->id)
            ->with('success', 'Talebiniz başarıyla oluşturuldu ve işleme alınacaktır.');
    } catch (\Exception $e) {
        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}

    /**
     * Talep detayı
     * Performans: Eager loading + güvenlik kontrolü
     */
    public function show(int $requestId): View|RedirectResponse
    {
        $userId = Auth::id();

        $request = $this->requestService->getRequestDetails($requestId, $userId);

        if (!$request) {
            return redirect()->route('requests.index')
                ->with('error', 'Talep bulunamadı veya size ait değil.');
        }

        return view('requests.show', compact('request'));
    }

    /**
     * Talep silme
     */
    public function destroy(int $requestId): RedirectResponse
    {
        try {
            $userId = Auth::id();

            $this->requestService->deleteRequest($requestId, $userId);

            return redirect()->route('requests.index')
                ->with('success', 'Talep başarıyla silindi.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
