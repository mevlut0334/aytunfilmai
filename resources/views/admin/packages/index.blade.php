@extends('layouts.admin')

@section('title', 'Paketler - Admin Panel')
@section('page-title', 'Paketler')

@section('content')
<div class="container-fluid">
    <!-- Yeni Paket Ekle Butonu -->
    <div class="mb-4">
        <a href="{{ route('admin.packages.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Yeni Paket Ekle
        </a>
    </div>

    <!-- Paket Listesi -->
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
                                <th style="width: 80px;">ID</th>
                                <th>Paket Adı</th>
                                <th>Açıklama</th>
                                <th style="width: 120px;">Token</th>
                                <th style="width: 120px;">Fiyat</th>
                                <th style="width: 120px;">Durum</th>
                                <th style="width: 150px;" class="text-center">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($packages as $package)
                                <tr>
                                    <td><strong>#{{ $package->id }}</strong></td>
                                    <td>
                                        <strong>{{ $package->name }}</strong>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $package->description ? Str::limit($package->description, 50) : '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ number_format($package->token_amount, 0) }} Token
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($package->price, 2) }} ₺</strong>
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
