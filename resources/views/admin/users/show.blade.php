@extends('layouts.admin')

@section('title', 'Kullanıcı Detayı - Admin Panel')
@section('page-title', 'Kullanıcı Detayı: ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sol: Kullanıcı Bilgileri ve İstatistikler -->
        <div class="col-lg-4 mb-4">
            <!-- Kullanıcı Bilgileri -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-person-circle"></i> Kullanıcı Bilgileri</h6>
                </div>
                <div class="card-body text-center">
                    <i class="bi bi-person-circle display-1 text-primary mb-3"></i>
                    <h4 class="mb-3">{{ $user->name }}</h4>

                    <ul class="list-group list-group-flush text-start">
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>ID:</strong></span>
                            <span>#{{ $user->id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>E-posta:</strong></span>
                            <span class="text-break">{{ $user->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>Telefon:</strong></span>
                            <span>{{ $user->phone }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>Token Bakiyesi:</strong></span>
                            <span class="badge bg-gradient-primary text-white">
                                {{ number_format($user->token_balance, 0) }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span><strong>Kayıt Tarihi:</strong></span>
                            <span>{{ $user->created_at->format('d.m.Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- İstatistikler -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-graph-up"></i> İstatistikler</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="bi bi-cart-check text-info"></i> Toplam Sipariş</span>
                            <strong>{{ $stats['total_orders'] }}</strong>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-info" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="bi bi-cash-stack text-success"></i> Toplam Harcama</span>
                            <strong>{{ number_format($stats['total_spent'], 2) }} ₺</strong>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="bi bi-film text-primary"></i> Toplam Talep</span>
                            <strong>{{ $stats['total_requests'] }}</strong>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-primary" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="bi bi-clock text-warning"></i> Bekleyen Talep</span>
                            <strong>{{ $stats['pending_requests'] }}</strong>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-warning" style="width: 100%"></div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="bi bi-check-circle text-success"></i> Tamamlanan Talep</span>
                            <strong>{{ $stats['completed_requests'] }}</strong>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Geri Dön -->
            <div class="mt-3">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left"></i> Kullanıcı Listesine Dön
                </a>
            </div>
        </div>

        <!-- Sağ: Son İşlemler -->
        <div class="col-lg-8">
            <!-- Son Siparişler -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="bi bi-cart-check"></i> Son Siparişler ({{ $user->orders->count() }})</h6>
                </div>
                <div class="card-body p-0">
                    @if($user->orders->isEmpty())
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-4 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">Henüz sipariş yok</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sipariş No</th>
                                        <th>Tutar</th>
                                        <th>Durum</th>
                                        <th>Tarih</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->orders as $order)
                                        <tr>
                                            <td><strong>#{{ $order->id }}</strong></td>
                                            <td>{{ number_format($order->final_amount, 2) }} ₺</td>
                                            <td>
                                                @if($order->status === 'completed')
                                                    <span class="badge bg-success">Tamamlandı</span>
                                                @elseif($order->status === 'pending')
                                                    <span class="badge bg-warning text-dark">Beklemede</span>
                                                @else
                                                    <span class="badge bg-danger">İptal</span>
                                                @endif
                                            </td>
                                            <td><small>{{ $order->created_at->format('d.m.Y H:i') }}</small></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Son Talepler -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="bi bi-film"></i> Son Talepler ({{ $user->requests->count() }})</h6>
                </div>
                <div class="card-body p-0">
                    @if($user->requests->isEmpty())
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-4 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">Henüz talep yok</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Başlık</th>
                                        <th>Durum</th>
                                        <th>Tarih</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->requests as $request)
                                        <tr>
                                            <td><strong>#{{ $request->id }}</strong></td>
                                            <td>{{ Str::limit($request->title, 40) }}</td>
                                            <td>
                                                @if($request->status === 'pending')
                                                    <span class="badge bg-warning text-dark">Beklemede</span>
                                                @elseif($request->status === 'processing')
                                                    <span class="badge bg-info">İşleniyor</span>
                                                @elseif($request->status === 'completed')
                                                    <span class="badge bg-success">Tamamlandı</span>
                                                @else
                                                    <span class="badge bg-danger">Başarısız</span>
                                                @endif
                                            </td>
                                            <td><small>{{ $request->created_at->format('d.m.Y H:i') }}</small></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Token İşlemleri -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="bi bi-coin"></i> Son Token İşlemleri ({{ $user->tokenTransactions->count() }})</h6>
                </div>
                <div class="card-body p-0">
                    @if($user->tokenTransactions->isEmpty())
                        <div class="text-center py-4">
                            <i class="bi bi-inbox display-4 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">Henüz token işlemi yok</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tip</th>
                                        <th>Miktar</th>
                                        <th>Açıklama</th>
                                        <th>Tarih</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->tokenTransactions as $transaction)
                                        <tr>
                                            <td>
                                                @if($transaction->type === 'credit')
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-plus-circle"></i> Yükleme
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-dash-circle"></i> Harcama
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong class="{{ $transaction->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                                    {{ $transaction->type === 'credit' ? '+' : '-' }}{{ number_format($transaction->amount, 0) }}
                                                </strong>
                                            </td>
                                            <td><small>{{ Str::limit($transaction->description, 40) }}</small></td>
                                            <td><small>{{ $transaction->created_at->format('d.m.Y H:i') }}</small></td>
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

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
</style>
@endpush
@endsection
