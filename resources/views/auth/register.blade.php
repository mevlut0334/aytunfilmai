@extends('layouts.app')

@section('title', 'Kayıt Ol - Aytun Film AI')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-person-plus"></i> Kayıt Ol</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('register.store') }}" method="POST">
                        @csrf

                        <!-- Ad Soyad -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Ad Soyad <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- E-posta -->
                        <div class="mb-3">
                            <label for="email" class="form-label">E-posta <span class="text-danger">*</span></label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Telefon -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefon <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   placeholder="+90 555 123 4567"
                                   required>
                            <small class="form-text text-muted">Sadece rakam ve + işareti kullanabilirsiniz.</small>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Şifre -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Şifre <span class="text-danger">*</span></label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   required>
                            <small class="form-text text-muted">En az 8 karakter olmalıdır.</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Şifre Onay -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Şifre Onay <span class="text-danger">*</span></label>
                            <input type="password"
                                   class="form-control"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   required>
                        </div>

                        <hr>

                        <!-- Onaylar Başlık -->
                        <h5 class="mb-3">Onaylar <span class="text-danger">*</span></h5>

                        <!-- Kullanım Koşulları -->
                        <div class="form-check mb-2">
                            <input class="form-check-input @error('terms_accepted') is-invalid @enderror"
                                   type="checkbox"
                                   name="terms_accepted"
                                   id="terms_accepted"
                                   value="1"
                                   {{ old('terms_accepted') ? 'checked' : '' }}
                                   required>
                            <label class="form-check-label" for="terms_accepted">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Kullanım Koşulları</a>'nı okudum ve kabul ediyorum. <span class="text-danger">*</span>
                            </label>
                            @error('terms_accepted')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Telif Hakları -->
                        <div class="form-check mb-2">
                            <input class="form-check-input @error('copyright_accepted') is-invalid @enderror"
                                   type="checkbox"
                                   name="copyright_accepted"
                                   id="copyright_accepted"
                                   value="1"
                                   {{ old('copyright_accepted') ? 'checked' : '' }}
                                   required>
                            <label class="form-check-label" for="copyright_accepted">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#copyrightModal">Telif Hakları Beyanı</a>'nı okudum ve kabul ediyorum. <span class="text-danger">*</span>
                            </label>
                            @error('copyright_accepted')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- KVKK -->
                        <div class="form-check mb-2">
                            <input class="form-check-input @error('kvkk_accepted') is-invalid @enderror"
                                   type="checkbox"
                                   name="kvkk_accepted"
                                   id="kvkk_accepted"
                                   value="1"
                                   {{ old('kvkk_accepted') ? 'checked' : '' }}
                                   required>
                            <label class="form-check-label" for="kvkk_accepted">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#kvkkModal">KVKK Aydınlatma Metni</a>'ni okudum ve kabul ediyorum. <span class="text-danger">*</span>
                            </label>
                            @error('kvkk_accepted')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kişisel Verilerin İşlenmesi -->
                        <div class="form-check mb-4">
                            <input class="form-check-input @error('personal_data_accepted') is-invalid @enderror"
                                   type="checkbox"
                                   name="personal_data_accepted"
                                   id="personal_data_accepted"
                                   value="1"
                                   {{ old('personal_data_accepted') ? 'checked' : '' }}
                                   required>
                            <label class="form-check-label" for="personal_data_accepted">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#personalDataModal">Kişisel Verilerin İşlenmesi Onayı</a>'nı okudum ve kabul ediyorum. <span class="text-danger">*</span>
                            </label>
                            @error('personal_data_accepted')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-person-plus"></i> Kayıt Ol
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <p class="mb-0">Zaten hesabınız var mı? <a href="{{ route('login') }}">Giriş Yapın</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Kullanım Koşulları Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kullanım Koşulları</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Kullanım koşulları metni buraya gelecek...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>

<!-- Telif Hakları Modal -->
<div class="modal fade" id="copyrightModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Telif Hakları Beyanı</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Telif hakları beyanı metni buraya gelecek...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>

<!-- KVKK Modal -->
<div class="modal fade" id="kvkkModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">KVKK Aydınlatma Metni</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>KVKK aydınlatma metni buraya gelecek...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>

<!-- Kişisel Verilerin İşlenmesi Modal -->
<div class="modal fade" id="personalDataModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kişisel Verilerin İşlenmesi Onayı</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Kişisel verilerin işlenmesi onayı metni buraya gelecek...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            </div>
        </div>
    </div>
</div>
@endsection
