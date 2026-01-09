@extends('layouts.admin')

@section('title', 'Kullanıcılar - Admin Panel')
@section('page-title', 'Kullanıcılar')

@section('content')
<div class="container-fluid">
    <!-- Filtreleme -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h6 class="mb-0"><i class="bi bi-funnel"></i> Filtreleme</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.index') }}" method="GET">
                <div class="row g-3">
                    <!-- E-posta -->
                    <div class="col-md-6">
                        <label class="form-label">E-posta</label>
                        <input type="text" name="email" class="form-control"
                               placeholder="E-posta ile ara..."
                               value="{{ request('email') }}">
                    </div>

                    <!-- Butonlar -->
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search"></i> Ara
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Temizle
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Kullanıcı Listesi -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-people"></i> Kullanıcılar ({{ $users->total() }})</h5>
        </div>
        <div class="card-body p-0">
            @if($users->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <p class="text-muted mt-3">Kullanıcı bulunamadı</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Ad Soyad</th>
                                <th>E-posta</th>
                                <th style="width: 150px;">Telefon</th>
                                <th style="width: 120px;">Token Bakiyesi</th>
                                <th style="width: 100px;">Siparişler</th>
                                <th style="width: 100px;">Talepler</th>
                                <th style="width: 150px;">Kayıt Tarihi</th>
                                <th style="width: 100px;" class="text-center">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr onclick="window.location='{{ route('admin.users.show', $user->id) }}'" style="cursor: pointer;">
                                    <td><strong>#{{ $user->id }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-person-circle me-2 text-primary"></i>
                                            <strong>{{ $user->name }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <small>{{ $user->email }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $user->phone }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-gradient-primary text-white">
                                            {{ number_format($user->token_balance, 0) }} Token
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->orders_count > 0)
                                            <span class="badge bg-info">
                                                {{ $user->orders_count }} Sipariş
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->requests_count > 0)
                                            <span class="badge bg-warning text-dark">
                                                {{ $user->requests_count }} Talep
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $user->created_at->format('d.m.Y') }}<br>
                                            {{ $user->created_at->format('H:i') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.users.show', $user->id) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Detay">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    {{ $users->firstItem() }} - {{ $users->lastItem() }} / {{ $users->total() }} kayıt
                                </small>
                            </div>
                            <div>
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            @endif
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
