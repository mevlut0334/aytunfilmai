<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\PackageServiceInterface;
use Illuminate\View\View;

class PackageController extends Controller
{
    protected PackageServiceInterface $packageService;

    /**
     * Dependency Injection
     */
    public function __construct(PackageServiceInterface $packageService)
    {
        $this->packageService = $packageService;
    }

    /**
     * Paket listesi (Kullanıcı tarafı)
     * Performans: Sadece aktif paketler
     */
    public function index(): View
    {
        $packages = $this->packageService->getActivePackages();

        return view('packages.index', compact('packages'));
    }
}
