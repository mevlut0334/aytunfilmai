@extends('layouts.admin')

@section('title', 'Sipariş Detayı - Admin Panel')
@section('page-title', 'Sipariş Detayı: #' . $order->id)

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sol: Sipariş Bilgileri -->
        <div class="col-lg-8 mb-4">
            <!-- Kullanıcı Bilgileri -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-person-circle"></i> Kullanıcı Bilgileri</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Ad Soyad:</strong> {{ $order->user->name }}</p>
                            <p class="mb-2"><strong>E-posta:</strong> {{ $order->user->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Telefon:</strong> {{ $order->user->phone }}</p>
                            <p class="mb-0"><strong>Kullanıcı ID:</strong> #{{ $order->user->id }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sipariş Kalemleri -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="bi bi-box-seam"></i> Sipariş Kalemleri</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Paket</th>
                                    <th style="width: 100px;" class="text-center">Adet</th>
                                    <th style="width: 120px;" class="text-end">Birim Fiyat</th>
                                    <th style="width: 120px;" class="text-end">Toplam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->package->name }}</strong><br>
                                            <small class="text-muted">{{ number_format($item->package->token_amount, 0) }} Token</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $item->quantity }}</span>
                                        </td>
                                        <td class="text-end">
                                            {{ number_format($item->unit_price, 2) }} ₺
                                        </td>
                                        <td class="text-end">
                                            <strong>{{ number_format($item->subtotal, 2) }} ₺</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Toplam Token:</strong></td>
                                    <td class="text-end">
                                        <strong class="text-warning">
                                            <i class="bi bi-coin"></i>
                                            {{ number_format($order->orderItems->sum(function($item) {
                                                return $item->package->token_amount * $item->quantity;
                                            }), 0) }}
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Kupon Bilgisi (Varsa) -->
            @if($order->coupon)
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-ticket-perforated"></i> Kullanılan Kupon</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-dark fs-6">{{ $order->coupon->code }}</span>
                                <p class="mb-0 mt-2">
                                    @if($order->coupon->type === 'percentage')
                                        <small>%{{ number_format($order->coupon->discount_value, 0) }} İndirim</small>
                                    @else
                                        <small>{{ number_format($order->coupon->discount_value, 2) }} ₺ İndirim</small>
                                    @endif
                                </p>
                            </div>
                            <div class="text-end">
                                <strong class="text-success">-{{ number_format($order->discount_amount, 2) }} ₺</strong>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sağ: Sipariş Özeti -->
        <div class="col-lg-4">
            <!-- Sipariş Durumu ve Onaylama -->
            <div class="card shadow-sm mb-3">
                <div class="card-header
                    @if($order->status === 'pending') bg-warning text-dark
                    @elseif($order->status === 'completed') bg-success text-white
                    @else bg-danger text-white
                    @endif">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Sipariş Durumu</h6>
                </div>
                <div class="card-body text-center">
                    @if($order->status === 'pending')
                        <i class="bi bi-clock display-1 text-warning"></i>
                        <h5 class="mt-3">Beklemede</h5>
                        <p class="text-muted small">Havale/EFT onayı bekleniyor</p>

                        <!-- Onaylama Butonu -->
                        <form action="{{ route('admin.orders.approve', $order->id) }}" method="POST" onsubmit="return confirm('Bu siparişi onaylamak istediğinize emin misiniz? Tokenlar kullanıcıya yüklenecektir.')">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg w-100 mt-3">
                                <i class="bi bi-check-circle"></i> Ödemeyi Onayla
                            </button>
                        </form>

                        <div class="alert alert-info mt-3 text-start small">
                            <i class="bi bi-info-circle"></i>
                            <strong>Onay Sonrası:</strong>
                            <ul class="mb-0 mt-1">
                                <li>Sipariş durumu "Tamamlandı" olur</li>
                                <li>Tokenlar otomatik yüklenir</li>
                                <li>Kupon kullanımı kaydedilir</li>
                            </ul>
                        </div>

                    @elseif($order->status === 'completed')
                        <i class="bi bi-check-circle display-1 text-success"></i>
                        <h5 class="mt-3">Tamamlandı</h5>
                        @if($order->payment_date)
                            <small class="text-muted">{{ $order->payment_date->format('d.m.Y H:i') }}</small>
                        @endif

                        <div class="alert alert-success mt-3 text-start small">
                            <i class="bi bi-check-circle"></i>
                            Bu sipariş onaylanmış ve tokenlar kullanıcıya yüklenmiştir.
                        </div>

                    @else
                        <i class="bi bi-x-circle display-1 text-danger"></i>
                        <h5 class="mt-3">Başarısız</h5>

                        <div class="alert alert-danger mt-3 text-start small">
                            <i class="bi bi-x-circle"></i>
                            Bu sipariş başarısız olarak işaretlenmiştir.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Fiyat Detayları -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="bi bi-calculator"></i> Fiyat Detayları</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Ara Toplam:</span>
                        <strong>{{ number_format($order->total_amount, 2) }} ₺</strong>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>İndirim:</span>
                            <strong>-{{ number_format($order->discount_amount, 2) }} ₺</strong>
                        </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong class="fs-5">TOPLAM:</strong>
                        <strong class="fs-5 text-primary">{{ number_format($order->final_amount, 2) }} ₺</strong>
                    </div>
                </div>
            </div>

            <!-- Sipariş Bilgileri -->
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="bi bi-calendar"></i> Sipariş Bilgileri</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Sipariş No:</strong><br>
                        <span class="text-muted">#{{ $order->id }}</span>
                    </p>
                    <p class="mb-2">
                        <strong>Oluşturma Tarihi:</strong><br>
                        <span class="text-muted">{{ $order->created_at->format('d.m.Y H:i') }}</span>
                    </p>
                    @if($order->transaction_id)
                        <p class="mb-0">
                            <strong>İşlem No:</strong><br>
                            <span class="text-muted">{{ $order->transaction_id }}</span>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Geri Dön -->
            <div class="mt-3">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left"></i> Sipariş Listesine Dön
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
