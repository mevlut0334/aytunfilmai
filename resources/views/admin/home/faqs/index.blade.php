@extends('layouts.admin')

@section('title', 'SSS Yönetimi')

@section('content')
<div class="container-fluid px-4">
    <!-- Başlık -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">
            <i class="bi bi-question-circle"></i> SSS Yönetimi
        </h1>
        <div>
            <a href="{{ route('admin.home.faqs.create') }}" class="btn btn-success me-2">
                <i class="bi bi-plus-circle"></i> Yeni SSS Ekle
            </a>
            <a href="{{ route('admin.home.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Geri Dön
            </a>
        </div>
    </div>

    <!-- Bilgilendirme -->
    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill"></i>
        Ana sayfada <strong>accordion</strong> yapısında gösterilecek sıkça sorulan soruları buradan yönetebilirsiniz.
    </div>

    <!-- SSS Listesi -->
    <div class="card shadow-sm">
        <div class="card-header bg-warning d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Sıkça Sorulan Sorular ({{ $faqs->count() }})</h5>
            <a href="{{ route('admin.home.faqs.create') }}" class="btn btn-dark btn-sm">
                <i class="bi bi-plus-circle"></i> Ekle
            </a>
        </div>
        <div class="card-body p-0">
            @if($faqs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50">Sıra</th>
                                <th>Soru</th>
                                <th>Cevap</th>
                                <th width="100">Durum</th>
                                <th width="150">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($faqs as $faq)
                                <tr>
                                    <!-- Sıra -->
                                    <td>
                                        <span class="badge bg-secondary">{{ $faq->order }}</span>
                                    </td>

                                    <!-- Soru -->
                                    <td>
                                        <strong>{{ $faq->question }}</strong>
                                    </td>

                                    <!-- Cevap -->
                                    <td>
                                        <small class="text-muted">
                                            {{ Str::limit($faq->answer, 80) }}
                                        </small>
                                    </td>

                                    <!-- Durum -->
                                    <td>
                                        @if($faq->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Pasif</span>
                                        @endif
                                    </td>

                                    <!-- İşlemler -->
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.home.faqs.edit', $faq->id) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Düzenle">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.home.faqs.destroy', $faq->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Bu SSS\'yi silmek istediğinize emin misiniz?')"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Sil">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h5 class="mt-3">Henüz SSS Yok</h5>
                    <p class="text-muted">İlk soruyu eklemek için yukarıdaki butonu kullanın.</p>
                    <a href="{{ route('admin.home.faqs.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> İlk Soruyu Ekle
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
