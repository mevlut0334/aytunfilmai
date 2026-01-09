@extends('layouts.admin')

@section('title', 'İstatistikler - Admin Panel')
@section('page-title', 'İstatistikler')

@section('content')
<div class="container-fluid">
    <!-- Filtreleme -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h6 class="mb-0"><i class="bi bi-funnel"></i> Zaman Filtresi</h6>
        </div>
        <div class="card-body">
            <!-- Hızlı Filtreler -->
            <div class="btn-group mb-3" role="group">
                <a href="{{ route('admin.statistics.index', ['filter' => 'today']) }}"
                   class="btn {{ $filter === 'today' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-calendar-day"></i> Bugün
                </a>
                <a href="{{ route('admin.statistics.index', ['filter' => 'week']) }}"
                   class="btn {{ $filter === 'week' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-calendar-week"></i> Bu Hafta
                </a>
                <a href="{{ route('admin.statistics.index', ['filter' => 'month']) }}"
                   class="btn {{ $filter === 'month' ? 'btn-primary' : 'btn-outline-primary' }}">
                    <i class="bi bi-calendar-month"></i> Bu Ay
                </a>
            </div>

            <!-- Özel Tarih Aralığı -->
            <form action="{{ route('admin.statistics.index') }}" method="GET" class="mt-3">
                <input type="hidden" name="filter" value="custom">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Başlangıç Tarihi</label>
                        <input type="date"
                               name="start_date"
                               class="form-control"
                               value="{{ request('start_date', $startDate ? $startDate->format('Y-m-d') : '') }}"
                               required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Bitiş Tarihi</label>
                        <input type="date"
                               name="end_date"
                               class="form-control"
                               value="{{ request('end_date', $endDate ? $endDate->format('Y-m-d') : '') }}"
                               required>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-search"></i> Özel Tarih Ara
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Seçili Filtre Bilgisi -->
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i>
        <strong>Seçili Dönem:</strong> {{ $filterLabel }}
        <small class="ms-2">({{ $startDate->format('d.m.Y') }} - {{ $endDate->format('d.m.Y') }})</small>
    </div>

    <!-- İstatistik Kartları -->
    <div class="row">
        <!-- Toplam Gelir -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-currency-exchange display-1 text-success"></i>
                    </div>
                    <h6 class="text-muted text-uppercase mb-2">Toplam Gelir</h6>
                    <h1 class="display-3 fw-bold text-success mb-0">
                        {{ number_format($totalRevenue, 2) }} ₺
                    </h1>
                    <small class="text-muted">Tamamlanan siparişlerden</small>
                </div>
                <div class="card-footer bg-success bg-opacity-10 text-center">
                    <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="text-success text-decoration-none">
                        <i class="bi bi-arrow-right-circle"></i> Siparişleri Gör
                    </a>
                </div>
            </div>
        </div>

        <!-- Toplam Talep -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-film display-1 text-primary"></i>
                    </div>
                    <h6 class="text-muted text-uppercase mb-2">Toplam Talep</h6>
                    <h1 class="display-3 fw-bold text-primary mb-0">
                        {{ number_format($totalRequests, 0) }}
                    </h1>
                    <small class="text-muted">Oluşturulan film talebi</small>
                </div>
                <div class="card-footer bg-primary bg-opacity-10 text-center">
                    <a href="{{ route('admin.requests.index') }}" class="text-primary text-decoration-none">
                        <i class="bi bi-arrow-right-circle"></i> Talepleri Gör
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Detaylı Bilgi -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Bilgilendirme</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li><strong>Toplam Gelir:</strong> Sadece "Tamamlandı" durumundaki siparişlerin toplam tutarı hesaplanır.</li>
                        <li><strong>Toplam Talep:</strong> Seçili tarih aralığında oluşturulan tüm film talepleri (durum fark etmez).</li>
                        <li><strong>Tarih Aralığı:</strong> Başlangıç ve bitiş tarihleri dahil edilir.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .btn-group .btn {
        min-width: 130px;
    }
</style>
@endpush
@endsection
