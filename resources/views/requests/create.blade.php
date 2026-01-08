@extends('layouts.app')

@section('title', 'Film Talebi Oluştur - Aytun Film AI')

@section('content')
<div class="container py-5">
    <!-- Başlık -->
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-film"></i> Film Talebi Oluştur</h2>
            <p class="text-muted">Film talebinizi oluşturun ve yapay zeka ile film üretin.</p>
        </div>
    </div>

    <form action="{{ route('requests.store') }}" method="POST" enctype="multipart/form-data" id="requestForm">
        @csrf

        <div class="row">
            <!-- Sol: Form Alanları -->
            <div class="col-lg-8 mb-4">
                <!-- Film Bilgileri -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Film Bilgileri</h5>
                    </div>
                    <div class="card-body">
                        <!-- Başlık -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Film Başlığı <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title') }}"
                                   maxlength="200"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Açıklama -->
                        <div class="mb-0">
                            <label for="description" class="form-label">Film Açıklaması / Senaryo <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="6"
                                      required>{{ old('description') }}</textarea>
                            <small class="text-muted">En az 50 karakter olmalıdır. Filminizin hikayesini detaylı anlatın.</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Karakterler -->
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-people"></i> Karakterler (Opsiyonel)</h5>
                        <button type="button" class="btn btn-sm btn-light" id="addCharacterBtn">
                            <i class="bi bi-plus-circle"></i> Karakter Ekle
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle-fill"></i>
                            İsterseniz karakterler ekleyebilirsiniz. Her karakter için <strong>farklı açılardan çekilmiş en az 5 görsel</strong> yüklemeniz gerekir.
                        </div>

                        <!-- Karakterler Listesi -->
                        <div id="charactersList"></div>
                    </div>
                </div>
            </div>

            <!-- Sağ: Bilgilendirme -->
            <div class="col-lg-4">
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h6 class="card-title"><i class="bi bi-lightbulb-fill text-warning"></i> İpuçları</h6>
                        <ul class="small mb-0">
                            <li>Film başlığını kısa ve açıklayıcı tutun</li>
                            <li>Açıklamada filminizin hikayesini detaylı anlatın</li>
                            <li>Karakter eklemek opsiyoneldir</li>
                            <li>Her karakter için en az 5 farklı açıdan çekilmiş görsel yükleyin</li>
                            <li>Görseller: JPG, JPEG, PNG (Max 5MB)</li>
                        </ul>
                    </div>
                </div>

                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title"><i class="bi bi-camera-fill text-info"></i> Görsel Önerileri</h6>
                        <ul class="small mb-0">
                            <li>Ön açıdan (yüz net görünsün)</li>
                            <li>Sol yan açıdan</li>
                            <li>Sağ yan açıdan</li>
                            <li>Hafif aşağıdan</li>
                            <li>Yakın plan (detay)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Butonlar -->
        <div class="row mt-4">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-send"></i> Talebi Gönder
                </button>
                <a href="{{ route('requests.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-x"></i> İptal
                </a>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let characterCount = 0;
const maxCharacters = 5;

// Karakter Ekle Butonu
document.getElementById('addCharacterBtn').addEventListener('click', function() {
    if (characterCount >= maxCharacters) {
        alert('En fazla 5 karakter ekleyebilirsiniz.');
        return;
    }

    characterCount++;
    addCharacter(characterCount);
});

// Karakter HTML'i Oluştur
function addCharacter(index) {
    const characterHtml = `
        <div class="character-item border rounded p-3 mb-3" data-character="${index}">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0"><i class="bi bi-person-fill"></i> Karakter ${index}</h6>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeCharacter(${index})">
                    <i class="bi bi-trash"></i> Sil
                </button>
            </div>

            <!-- Karakter Adı -->
            <div class="mb-3">
                <label for="character_name_${index}" class="form-label">Karakter Adı <span class="text-danger">*</span></label>
                <input type="text"
                       class="form-control"
                       id="character_name_${index}"
                       name="characters[${index - 1}][name]"
                       maxlength="100"
                       required>
            </div>

            <!-- Görseller -->
            <div class="mb-0">
                <label for="character_images_${index}" class="form-label">
                    Karakter Görselleri <span class="text-danger">*</span>
                    <small class="text-muted">(En az 5, en fazla 10 görsel)</small>
                </label>
                <input type="file"
                       class="form-control"
                       id="character_images_${index}"
                       name="characters[${index - 1}][images][]"
                       accept="image/jpeg,image/jpg,image/png"
                       multiple
                       required>
                <small class="text-muted">Farklı açılardan çekilmiş en az 5 görsel yükleyin.</small>
            </div>
        </div>
    `;

    document.getElementById('charactersList').insertAdjacentHTML('beforeend', characterHtml);
}

// Karakter Sil
function removeCharacter(index) {
    const characterItem = document.querySelector(`[data-character="${index}"]`);
    if (characterItem) {
        characterItem.remove();
        characterCount--;
    }
}

// Form Submit Kontrolü
document.getElementById('requestForm').addEventListener('submit', function(e) {
    const fileInputs = document.querySelectorAll('input[type="file"]');

    fileInputs.forEach(input => {
        if (input.files.length > 0 && input.files.length < 5) {
            e.preventDefault();
            alert('Her karakter için en az 5 görsel yüklemelisiniz.');
            return false;
        }

        if (input.files.length > 10) {
            e.preventDefault();
            alert('Her karakter için en fazla 10 görsel yükleyebilirsiniz.');
            return false;
        }
    });
});
</script>
@endpush
@endsection
