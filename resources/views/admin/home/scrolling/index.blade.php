@extends('layouts.admin')

@section('title', 'Kaydırmalı Görseller')

@section('content')
<div class="container-fluid px-4">
    <!-- Başlık -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">
            <i class="bi bi-arrow-left-right"></i> Kaydırmalı Görseller
        </h1>
        <div>
            <a href="{{ route('admin.home.scrolling.create') }}" class="btn btn-success me-2">
                <i class="bi bi-plus-circle"></i> Yeni Görsel Ekle
            </a>
            <a href="{{ route('admin.home.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Geri Dön
            </a>
        </div>
    </div>

    <!-- Bilgilendirme -->
    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill"></i>
        Ana sayfada <strong>sonsuz kaydırma</strong> efekti ile gösterilecek görselleri buradan yönetebilirsiniz.
    </div>

    <!-- Görsel Listesi -->
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Kaydırmalı Görseller ({{ $images->count() }})</h5>
            <a href="{{ route('admin.home.scrolling.create') }}" class="btn btn-light btn-sm">
                <i class="bi bi-plus-circle"></i> Ekle
            </a>
        </div>
        <div class="card-body p-0">
            @if($images->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50">Sıra</th>
                                <th width="150">Görsel</th>
                                <th>Başlık</th>
                                <th>Link</th>
                                <th width="100">Durum</th>
                                <th width="150">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($images as $image)
                                <tr>
                                    <!-- Sıra -->
                                    <td>
                                        <span class="badge bg-secondary">{{ $image->order }}</span>
                                    </td>

                                    <!-- Görsel -->
                                    <td>
                                        @if($image->image)
                                            <img src="{{ asset('storage/' . $image->image) }}"
                                                 alt="{{ $image->title }}"
                                                 class="img-thumbnail"
                                                 style="width: 120px; height: 80px; object-fit: cover;">
                                        @else
                                            <div class="bg-light border rounded d-flex align-items-center justify-content-center"
                                                 style="width: 120px; height: 80px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Başlık -->
                                    <td>
                                        <strong>{{ $image->title }}</strong>
                                    </td>

                                    <!-- Link -->
                                    <td>
                                        @if($image->link)
                                            <a href="{{ $image->link }}" target="_blank" class="text-primary">
                                                <i class="bi bi-link-45deg"></i> Link
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <!-- Durum -->
                                    <td>
                                        @if($image->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Pasif</span>
                                        @endif
                                    </td>

                                    <!-- İşlemler -->
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.home.scrolling.edit', $image->id) }}"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Düzenle">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.home.scrolling.destroy', $image->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Bu görseli silmek istediğinize emin misiniz?')"
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
                    <h5 class="mt-3">Henüz Görsel Yok</h5>
                    <p class="text-muted">İlk görseli eklemek için yukarıdaki butonu kullanın.</p>
                    <a href="{{ route('admin.home.scrolling.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> İlk Görseli Ekle
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
