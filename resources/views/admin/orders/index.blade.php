@extends('layouts.admin')

@section('title', 'Siparişler - Admin Panel')
@section('page-title', 'Siparişler')

@section('content')
<div class="container-fluid">
    <!-- Filtreleme -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h6 class="mb-0"><i class="bi bi-funnel"></i> Filtreleme</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.orders.index') }}" method="GET" id="filterForm">
                <div class="row g-3">
                    <!-- E-posta Arama -->
                    <div class="col-md-6">
                        <label class="form-label">E-posta</label>
                        <input type="text"
                               name="email"
                               id="emailInput"
                               class="form-control"
                               placeholder="En az 3 harf yazın..."
                               value="{{ request('email') }}"
                               autocomplete="off">
                        <small class="text-muted">3+ harf yazınca otomatik arar</small>
                    </div>

                    <!-- Durum -->
                    <div class="col-md-6">
                        <label class="form-label">Durum</label>
                        <select name="status" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Tüm Durumlar</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Beklemede</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Başarısız</option>
                        </select>
                    </div>

                    <!-- Butonlar -->
                    <div class="col-md-12 d-flex">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search"></i> Filtrele
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Temizle
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Sipariş Listesi -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-cart-check"></i> Siparişler ({{ $orders->total() }})</h5>
        </div>
        <div class="card-body p-0">
            @if($orders->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <p class="text-muted mt-3">Sipariş bulunamadı</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th style="width: 200px;">Kullanıcı</th>
                                <th style="width: 150px;">Toplam Tutar</th>
                                <th style="width: 150px;">İndirim</th>
                                <th style="width: 150px;">Ödenecek</th>
                                <th style="width: 120px;">Durum</th>
                                <th style="width: 150px;">Tarih</th>
                                <th style="width: 100px;" class="text-center">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr onclick="window.location='{{ route('admin.orders.show', $order->id) }}'" style="cursor: pointer;">
                                    <td><strong>#{{ $order->id }}</strong></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-person-circle me-2 text-primary"></i>
                                            <div>
                                                <div>{{ $order->user->name }}</div>
                                                <small class="text-muted">{{ $order->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($order->total_amount, 2) }} ₺</strong>
                                    </td>
                                    <td>
                                        @if($order->discount_amount > 0)
                                            <span class="text-danger">-{{ number_format($order->discount_amount, 2) }} ₺</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ number_format($order->final_amount, 2) }} ₺</strong>
                                    </td>
                                    <td>
                                        @if($order->status === 'pending')
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-clock"></i> Beklemede
                                            </span>
                                        @elseif($order->status === 'completed')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Tamamlandı
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle"></i> Başarısız
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $order->created_at->format('d.m.Y') }}<br>
                                            {{ $order->created_at->format('H:i') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.orders.show', $order->id) }}"
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
                @if($orders->hasPages())
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    {{ $orders->firstItem() }} - {{ $orders->lastItem() }} / {{ $orders->total() }} kayıt
                                </small>
                            </div>
                            <div>
                                {{ $orders->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Otomatik arama: 3+ harf yazıldığında form submit
    let typingTimer;
    const emailInput = document.getElementById('emailInput');
    const filterForm = document.getElementById('filterForm');

    emailInput.addEventListener('input', function() {
        clearTimeout(typingTimer);

        const value = this.value.trim();

        // 3+ karakter varsa veya boşsa form submit
        if (value.length >= 3 || value.length === 0) {
            typingTimer = setTimeout(function() {
                filterForm.submit();
            }, 500); // 500ms sonra submit (typing bittikten sonra)
        }
    });
</script>
@endpush
@endsection
