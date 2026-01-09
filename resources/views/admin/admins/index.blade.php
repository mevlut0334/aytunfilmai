@extends('layouts.admin')

@section('title', 'Admin Kullanıcılar - Admin Panel')
@section('page-title', 'Admin Kullanıcılar')

@section('content')
<div class="container-fluid">
    <!-- Yeni Admin Ekle Butonu -->
    <div class="mb-4">
        <a href="{{ route('admin.admins.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Yeni Admin Ekle
        </a>
    </div>

    <!-- Admin Listesi -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-person-badge"></i> Admin Kullanıcılar ({{ $admins->total() }})</h5>
        </div>
        <div class="card-body p-0">
            @if($admins->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <p class="text-muted mt-3">Admin kullanıcı bulunamadı</p>
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
                                <th style="width: 150px;">Kayıt Tarihi</th>
                                <th style="width: 100px;" class="text-center">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $admin)
                                <tr>
                                    <td><strong>#{{ $admin->id }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-person-badge me-2 text-danger"></i>
                                            <strong>{{ $admin->name }}</strong>
                                            @if($admin->id === auth()->id())
                                                <span class="badge bg-primary ms-2">Siz</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <small>{{ $admin->email }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $admin->phone }}</small>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $admin->created_at->format('d.m.Y') }}<br>
                                            {{ $admin->created_at->format('H:i') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        @if($admin->id !== auth()->id())
                                            <form action="{{ route('admin.admins.destroy', $admin->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Bu admin kullanıcıyı silmek istediğinize emin misiniz?');"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Sil">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($admins->hasPages())
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    {{ $admins->firstItem() }} - {{ $admins->lastItem() }} / {{ $admins->total() }} kayıt
                                </small>
                            </div>
                            <div>
                                {{ $admins->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
