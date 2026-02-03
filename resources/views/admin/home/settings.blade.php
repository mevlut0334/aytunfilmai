@extends('layouts.admin')

@section('title', 'Site Ayarları - Ana Sayfa')

@section('content')
<div class="container-fluid px-4">
    <!-- Başlık -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">
            <i class="bi bi-gear-fill"></i> Site Ayarları
        </h1>
        <a href="{{ route('admin.home.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Geri Dön
        </a>
    </div>

    <div class="row">
        <!-- Sol: Form -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Genel Ayarlar</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.home.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- WhatsApp Numarası -->
                        <div class="mb-4">
                            <label for="whatsapp_number" class="form-label">
                                WhatsApp Numarası <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-whatsapp text-success"></i>
                                </span>
                                <input type="text"
                                       class="form-control @error('whatsapp_number') is-invalid @enderror"
                                       id="whatsapp_number"
                                       name="whatsapp_number"
                                       placeholder="+905551234567"
                                       value="{{ old('whatsapp_number', $whatsappNumber) }}"
                                       required>
                            </div>
                            <small class="text-muted">
                                Örnek: +905551234567 (Ülke kodu ile birlikte)
                            </small>
                            @error('whatsapp_number')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Banka Alıcı Adı -->
                        <div class="mb-4">
                            <label for="bank_account_name" class="form-label">
                                Banka Alıcı Adı <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person-fill text-primary"></i>
                                </span>
                                <input type="text"
                                       class="form-control @error('bank_account_name') is-invalid @enderror"
                                       id="bank_account_name"
                                       name="bank_account_name"
                                       placeholder="Şirket Adı"
                                       value="{{ old('bank_account_name', $bankAccountName) }}"
                                       required>
                            </div>
                            @error('bank_account_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Banka IBAN -->
                        <div class="mb-4">
                            <label for="bank_iban" class="form-label">
                                Banka IBAN <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-bank2 text-warning"></i>
                                </span>
                                <input type="text"
                                       class="form-control @error('bank_iban') is-invalid @enderror"
                                       id="bank_iban"
                                       name="bank_iban"
                                       placeholder="TR00 0000 0000 0000 0000 0000 00"
                                       value="{{ old('bank_iban', $bankIban) }}"
                                       required>
                            </div>
                            <small class="text-muted">
                                Örnek: TR00 0000 0000 0000 0000 0000 00
                            </small>
                            @error('bank_iban')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Butonlar -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Kaydet
                            </button>
                            <a href="{{ route('admin.home.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> İptal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sağ: Bilgi Kartı -->
        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-info-circle-fill text-info"></i> Bilgilendirme
                    </h6>
                    <ul class="small mb-0">
                        <li class="mb-2">
                            <strong>WhatsApp Numarası:</strong> Footer'daki sticky bar'da gösterilir
                        </li>
                        <li class="mb-2">
                            Format: <code>+90XXXXXXXXXX</code>
                        </li>
                        <li class="mb-2">
                            Tıklandığında WhatsApp konuşması başlatır
                        </li>
                        <li class="mb-2">
                            <strong>Alıcı Adı:</strong> Ana sayfada ödeme bilgilerinde gösterilir
                        </li>
                        <li class="mb-0">
                            <strong>IBAN:</strong> Kopyalama butonu ile birlikte gösterilir
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Önizleme -->
            <div class="card bg-light mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="bi bi-eye text-primary"></i> Önizleme
                    </h6>
                    <p class="small text-muted mb-2">Ana sayfada şöyle görünecek:</p>
                    <div class="border rounded p-3 bg-white">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-person-fill text-primary me-2"></i>
                            <span class="small text-muted me-2">Alıcı:</span>
                            <strong class="small">Şirket Adı</strong>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-bank2 text-warning me-2"></i>
                            <span class="small text-muted me-2">IBAN:</span>
                            <span class="small font-monospace">TR00 0000...</span>
                            <i class="bi bi-copy text-primary ms-2" style="cursor:pointer; font-size:0.85rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
