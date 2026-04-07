@extends('layouts.admin')

@section('title', 'Paketler - Admin Panel')
@section('page-title', 'Paketler')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.packages.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Yeni Paket Ekle
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-box-seam"></i> Paketler ({{ $packages->count() }})</h5>
        </div>
        <div class="card-body p-0">
            @if($packages->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <p class="text-muted mt-3">Henüz paket yok</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Paket Adı</th>
                                <th style="width: 100px;">Token</th>
                                <th style="width: 100px;">Fiyat</th>
                                <th>Paddle Price ID</th>
                                <th style="width: 100px;">Durum</th>
                                <th style="width: 130px;" class="text-center">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($packages as $package)
                                <tr>
                                    <td><strong>#{{ $package->id }}</strong></td>
                                    <td>
                                        <strong>{{ $package->name }}</strong>
                                        @if($package->description)
                                            <br><small class="text-muted">{{ Str::limit($package->description, 40) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ number_format($package->token_amount, 0) }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>${{ number_format($package->price, 2) }}</strong>
                                    </td>
                                    <td>
                                        @if($package->paddle_price_id)
                                            <code class="small">{{ $package->paddle_price_id }}</code>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-exclamation-triangle"></i> Girilmemiş
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($package->is_active)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle"></i> Pasif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.packages.edit', $package->id) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Düzenle">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.packages.destroy', $package->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Bu paketi silmek istediğinize emin misiniz?');"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Sil">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
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
@endsection
