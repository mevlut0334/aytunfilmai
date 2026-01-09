<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Request;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\View\View;

class AdminStatisticsController extends Controller
{
    /**
     * İstatistikler sayfası
     * Filtreleme: today, week, month, custom
     */
    public function index(HttpRequest $request): View
    {
        // Varsayılan: Bugün
        $filter = $request->get('filter', 'today');
        $startDate = null;
        $endDate = null;

        // Filtre türüne göre tarih aralığını belirle
        switch ($filter) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                $filterLabel = 'Bugün';
                break;

            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $filterLabel = 'Bu Hafta';
                break;

            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $filterLabel = 'Bu Ay';
                break;

            case 'custom':
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                    $filterLabel = $startDate->format('d.m.Y') . ' - ' . $endDate->format('d.m.Y');
                } else {
                    // Özel tarih seçilmediyse bugüne dön
                    $startDate = Carbon::today();
                    $endDate = Carbon::today()->endOfDay();
                    $filterLabel = 'Bugün';
                    $filter = 'today';
                }
                break;

            default:
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                $filterLabel = 'Bugün';
                $filter = 'today';
        }

        // Toplam Gelir (Sadece completed siparişler)
        $totalRevenue = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('final_amount');

        // Toplam Talep Sayısı
        $totalRequests = Request::whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return view('admin.statistics.index', compact(
            'filter',
            'filterLabel',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalRequests'
        ));
    }
}
