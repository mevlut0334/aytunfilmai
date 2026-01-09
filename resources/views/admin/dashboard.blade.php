@extends('layouts.admin')

@section('title', 'Admin Dashboard - Aytun Film AI')

@section('content')
<div class="container py-5">
    <!-- Başlık -->
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-speedometer2"></i> Admin Dashboard</h2>
            <p class="text-muted">Sistem istatistikleri ve son talepler</p>
        </div>
    </div>

    <!-- İstatistik Kartları -->
    <div class="row mb-4">
        <!-- Toplam Talepler -->
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Toplam Talepler</h6>
                            <h2 class="mb-0">{{ $stats['total_requests'] }}</h2>
                        </div>
                        <i class="bi bi-film display-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bekleyen -->
        <div class="col-md-4 mb-3">
            <div class="card text-dark bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Bekleyen</h6>
                            <h2 class="mb-0">{{ $stats['pending_requests'] }}</h2>
                        </div>
                        <i class="bi bi-clock display-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- İşleniyor -->
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">İşleniyor</h6>
                            <h2 class="mb-0">{{ $stats['processing_requests'] }}</h2>
                        </div>
                        <i class="bi bi-arrow-repeat display-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tamamlanan -->
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Tamamlanan</h6>
                            <h2 class="mb-0">{{ $stats['completed_requests'] }}</h2>
                        </div>
                        <i class="bi bi-check-circle display-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Başarısız -->
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Başarısız</h6>
                            <h2 class="mb-0">{{ $stats['failed_requests'] }}</h2>
                        </div>
                        <i class="bi bi-x-circle display-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toplam Kullanıcılar -->
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Toplam Kullanıcılar</h6>
                            <h2 class="mb-0">{{ $stats['total_users'] }}</h2>
                        </div>
                        <i class="bi bi-people display-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Son Talepler -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Son Talepler</h5>
                    <a href="{{ route('admin.requests.index') }}" class="btn btn-sm btn-light">Tümünü Gör</a>
                </div>
                <div class="card-body p-0">
                    @if($recentRequests->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <p class="text-muted mt-3">Henüz talep yok</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Başlık</th>
                                        <th>Kullanıcı</th>
                                        <th>Durum</th>
                                        <th>İşleyen Admin</th>
                                        <th>Tarih</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentRequests as $request)
                                        <tr onclick="window.location='{{ route('admin.requests.show', $request->id) }}'" style="cursor: pointer;">
                                            <td><strong>#{{ $request->id }}</strong></td>
                                            <td>{{ Str::limit($request->title, 30) }}</td>
                                            <td>
                                                <i class="bi bi-person"></i> {{ $request->user->name }}
                                            </td>
                                            <td>
                                                @if($request->status === 'pending')
                                                    <span class="badge bg-warning text-dark">Beklemede</span>
                                                @elseif($request->status === 'processing')
                                                    <span class="badge bg-info text-dark">İşleniyor</span>
                                                @elseif($request->status === 'completed')
                                                    <span class="badge bg-success">Tamamlandı</span>
                                                @else
                                                    <span class="badge bg-danger">Başarısız</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($request->processedBy)
                                                    <i class="bi bi-person-check"></i> {{ $request->processedBy->name }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $request->created_at->format('d.m.Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.requests.show', $request->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i> Detay
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
