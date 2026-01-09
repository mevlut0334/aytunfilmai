@extends('layouts.admin')

@section('title', 'Talepler - Admin Panel')
@section('page-title', 'Talepler')

@section('content')
<div class="container-fluid">
    <!-- Filtreleme -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h6 class="mb-0"><i class="bi bi-funnel"></i> Filtreleme</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.requests.index') }}" method="GET" id="filterForm">
                <div class="row g-3">
                    <!-- Başlık Arama -->
                    <div class="col-md-3">
                        <label class="form-label">Başlık</label>
                        <input type="text"
                               name="title"
                               id="titleInput"
                               class="form-control"
                               placeholder="En az 3 harf yazın..."
                               value="{{ request('title') }}"
                               autocomplete="off">
                        <small class="text-muted">3+ harf yazınca otomatik arar</small>
                    </div>

                    <!-- Kullanıcı -->
                    <div class="col-md-3">
                        <label class="form-label">Kullanıcı</label>
                        <select name="user_id" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Tüm Kullanıcılar</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Durum -->
                    <div class="col-md-3">
                        <label class="form-label">Durum</label>
                        <select name="status" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Tüm Durumlar</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Beklemede</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>İşleniyor</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Başarısız</option>
                        </select>
                    </div>

                    <!-- İşleyen Admin -->
                    <div class="col-md-3">
                        <label class="form-label">İşleyen Admin</label>
                        <select name="processed_by" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Tüm Adminler</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}" {{ request('processed_by') == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Butonlar -->
                    <div class="col-md-12 d-flex">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search"></i> Filtrele
                        </button>
                        <a href="{{ route('admin.requests.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Temizle
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Talepler Listesi -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-film"></i> Talepler ({{ $requests->total() }})</h5>
        </div>
        <div class="card-body p-0">
            @if($requests->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <p class="text-muted mt-3">Talep bulunamadı</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th>Başlık</th>
                                <th style="width: 200px;">Kullanıcı</th>
                                <th style="width: 120px;">Durum</th>
                                <th style="width: 150px;">İşleyen Admin</th>
                                <th style="width: 100px;">Karakter</th>
                                <th style="width: 150px;">Tarih</th>
                                <th style="width: 100px;" class="text-center">İşlem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                                <tr onclick="window.location='{{ route('admin.requests.show', $request->id) }}'" style="cursor: pointer;">
                                    <td><strong>#{{ $request->id }}</strong></td>
                                    <td>{{ Str::limit($request->title, 40) }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-person-circle me-2 text-primary"></i>
                                            <div>
                                                <div>{{ $request->user->name }}</div>
                                                <small class="text-muted">{{ $request->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($request->status === 'pending')
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-clock"></i> Beklemede
                                            </span>
                                        @elseif($request->status === 'processing')
                                            <span class="badge bg-info text-dark">
                                                <i class="bi bi-arrow-repeat"></i> İşleniyor
                                            </span>
                                        @elseif($request->status === 'completed')
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
                                        @if($request->processedBy)
                                            <small>
                                                <i class="bi bi-person-check text-success"></i>
                                                {{ Str::limit($request->processedBy->name, 15) }}
                                            </small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($request->characters->count() > 0)
                                            <span class="badge bg-secondary">
                                                {{ $request->characters->count() }} Karakter
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $request->created_at->format('d.m.Y') }}<br>
                                            {{ $request->created_at->format('H:i') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.requests.show', $request->id) }}"
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
                @if($requests->hasPages())
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    {{ $requests->firstItem() }} - {{ $requests->lastItem() }} / {{ $requests->total() }} kayıt
                                </small>
                            </div>
                            <div>
                                {{ $requests->links() }}
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
    const titleInput = document.getElementById('titleInput');
    const filterForm = document.getElementById('filterForm');

    titleInput.addEventListener('input', function() {
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
