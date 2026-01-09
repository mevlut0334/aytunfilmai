@extends('layouts.admin')

@section('title', 'İndirim Kuponları - Admin Panel')
@section('page-title', 'İndirim Kuponları')

@section('content')
<div class="container-fluid">
    <!-- Yeni Kupon Ekle Butonu -->
    <div class="mb-4">
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Yeni Kupon Ekle
        </a>
    </div>

    <!-- Kupon Listesi -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-ticket-perforated"></i> Kuponlar ({{ $coupons->count() }})</h5>
        </div>
        <div class="card-body p-0">
            @if($coupons->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <p class="text-muted mt-3">Henüz kupon yok</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th style="width: 150px;">Kupon Kodu</th>
                                <th style="width: 100px;">Tip</th>
                                <th style="width: 120px;">İndirim</th>
                                <th style="width: 120px;">Min. Tutar</th>
                                <th style="width: 100px;">Kullanım</th>
                                <th style="width: 200px;">Geçerlilik</th>
                                <th style="width: 100px;">Durum</th>
                                <th style="width: 150px;" class="text-center">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupons as $coupon)
                                <tr>
                                    <td><strong>#{{ $coupon->id }}</strong></td>
                                    <td>
                                        <span class="badge bg-dark fs-6">{{ $coupon->code }}</span>
                                    </td>
                                    <td>
                                        @if($coupon->type === 'percentage')
                                            <span class="badge bg-info">Yüzde</span>
                                        @else
                                            <span class="badge bg-primary">Sabit</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>
                                            @if($coupon->type === 'percentage')
                                                %{{ number_format($coupon->discount_value, 0) }}
                                            @else
                                                {{ number_format($coupon->discount_value, 2) }} ₺
                                            @endif
                                        </strong>
                                    </td>
                                    <td>
                                        @if($coupon->min_amount > 0)
                                            <small>{{ number_format($coupon->min_amount, 2) }} ₺</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            {{ $coupon->usage_count }}
                                            @if($coupon->max_usage)
                                                / {{ $coupon->max_usage }}
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            @if($coupon->starts_at)
                                                {{ $coupon->starts_at->format('d.m.Y') }}
                                            @else
                                                -
                                            @endif
                                            →
                                            @if($coupon->expires_at)
                                                {{ $coupon->expires_at->format('d.m.Y') }}
                                            @else
                                                ∞
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        @if($coupon->is_active)
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
                                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Düzenle">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Bu kuponu silmek istediğinize emin misiniz?');"
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
