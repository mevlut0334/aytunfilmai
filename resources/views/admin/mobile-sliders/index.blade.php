@extends('layouts.admin')

@section('title', 'Mobil Slider Yönetimi')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mt-4">
                <i class="bi bi-phone"></i> Mobil Slider Yönetimi
            </h1>
            <p class="text-muted">Android ve iOS uygulaması için slider görsellerini yönetin.</p>
        </div>
        <a href="{{ route('admin.mobile-sliders.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Yeni Slider Ekle
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if($sliders->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                    <p class="mt-3">Henüz mobil slider eklenmemiş.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Görsel</th>
                                <th>Link</th>
                                <th>Sıra</th>
                                <th>Durum</th>
                                <th>Oluşturulma</th>
                                <th class="text-end">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sliders as $slider)
                                <tr>
                                    <td>
                                        <img src="{{ Storage::url($slider->image) }}"
                                             alt="Slider"
                                             style="height: 60px; width: 120px; object-fit: cover; border-radius: 6px;">
                                    </td>
                                    <td>
                                        @if($slider->link)
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;">
                                                {{ $slider->link }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $slider->order }}</span>
                                    </td>
                                    <td>
                                        @if($slider->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Pasif</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">
                                        {{ $slider->created_at->format('d.m.Y H:i') }}
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.mobile-sliders.edit', $slider->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Düzenle
                                        </a>
                                        <form action="{{ route('admin.mobile-sliders.destroy', $slider->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Bu slider\'ı silmek istediğinize emin misiniz?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i> Sil
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
