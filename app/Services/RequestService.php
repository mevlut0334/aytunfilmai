<?php

namespace App\Services;

use App\Models\Request;
use App\Repositories\Interfaces\RequestRepositoryInterface;
use App\Services\Interfaces\RequestServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RequestService implements RequestServiceInterface
{
    protected RequestRepositoryInterface $requestRepository;

    /**
     * Dependency Injection
     */
    public function __construct(RequestRepositoryInterface $requestRepository)
    {
        $this->requestRepository = $requestRepository;
    }

    /**
     * Talep oluştur (karakterler ve görseller ile)
     * Performans: Transaction kullanımı
     *
     * @param int $userId
     * @param array $data
     * @return Request
     * @throws \Exception
     */
    public function createRequest(int $userId, array $data): Request
    {
        return DB::transaction(function () use ($userId, $data) {
            // Talebi oluştur
            $request = $this->requestRepository->create([
                'user_id' => $userId,
                'title' => $data['title'],
                'description' => $data['description'],
                'video_format' => $data['video_format'] ?? 'horizontal', // Formdan gelen veri
                'status' => 'pending',
            ]);

            // Karakterler varsa ekle
            if (isset($data['characters']) && is_array($data['characters'])) {
                foreach ($data['characters'] as $characterData) {
                    // Karakter oluştur
                    $character = $this->requestRepository->addCharacter($request->id, [
                        'name' => $characterData['name'],
                    ]);

                    // Görselleri yükle
                    if (isset($characterData['images']) && is_array($characterData['images'])) {
                        // Minimum 5 görsel kontrolü
                        if (count($characterData['images']) < 5) {
                            throw new \Exception("Her karakter için en az 5 görsel yüklemelisiniz.");
                        }

                        foreach ($characterData['images'] as $index => $image) {
                            // Görseli storage'a kaydet
                            $path = $image->store(
                                "requests/{$request->id}/characters/{$character->id}",
                                'public'
                            );

                            // Veritabanına kaydet
                            $this->requestRepository->addCharacterImage(
                                $character->id,
                                $path,
                                $index + 1
                            );
                        }
                    }
                }
            }

            // TODO: Job'a gönder (AI işleme için)
            // ProcessFilmRequestJob::dispatch($request->id);

            return $request->fresh(['characters.images']);
        });
    }

    /**
     * Kullanıcının taleplerini getir
     * Performans: Eager loading
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserRequests(int $userId): Collection
    {
        return $this->requestRepository->getUserRequests($userId, [
            'characters.images' => function ($query) {
                $query->ordered()->limit(1); // Sadece ilk görseli getir (thumbnail için)
            }
        ]);
    }

    /**
     * Talep detayını getir
     * Performans: Eager loading + güvenlik kontrolü
     *
     * @param int $requestId
     * @param int $userId (Güvenlik için)
     * @return Request|null
     */
    public function getRequestDetails(int $requestId, int $userId): ?Request
    {
        $request = $this->requestRepository->findById($requestId, [
            'characters.images' => function ($query) {
                $query->ordered();
            }
        ]);

        // Güvenlik kontrolü: Talep kullanıcıya ait mi?
        if (!$request || $request->user_id !== $userId) {
            return null;
        }

        return $request;
    }

    /**
     * Talep sil
     * Performans: Storage temizliği
     *
     * @param int $requestId
     * @param int $userId (Güvenlik için)
     * @return bool
     * @throws \Exception
     */
    public function deleteRequest(int $requestId, int $userId): bool
    {
        $request = $this->requestRepository->findById($requestId);

        if (!$request) {
            throw new \Exception('Talep bulunamadı.');
        }

        // Güvenlik kontrolü: Talep kullanıcıya ait mi?
        if ($request->user_id !== $userId) {
            throw new \Exception('Bu talebi silme yetkiniz yok.');
        }

        // İş kuralı: Processing veya completed talepleri silemez
        if ($request->isProcessing()) {
            throw new \Exception('İşleme alınan talepler silinemez.');
        }

        if ($request->isCompleted()) {
            throw new \Exception('Tamamlanmış talepler silinemez.');
        }

        // Storage'dan görselleri sil
        Storage::disk('public')->deleteDirectory("requests/{$requestId}");

        return $this->requestRepository->delete($requestId);
    }

    /**
     * Tüm talepleri getir (Admin)
     * Performans: Eager loading + filtreleme
     *
     * @param array $filters
     * @return Collection
     */
    public function getAllRequests(array $filters = []): Collection
    {
        // Status filtresi varsa
        if (isset($filters['status'])) {
            return $this->requestRepository->getByStatus($filters['status'], [
                'user',
                'characters'
            ]);
        }

        return $this->requestRepository->getAll([
            'user',
            'characters'
        ]);
    }

    /**
     * Talep durumunu güncelle (Admin)
     * Admin takibi: İlk kez processing yapıldığında admin bilgisini kaydet
     *
     * @param int $requestId
     * @param string $status
     * @param int|null $adminId (Durumu güncelleyen admin)
     * @param array $additionalData (video_url, error_message)
     * @return bool
     * @throws \Exception
     */
    public function updateRequestStatus(int $requestId, string $status, ?int $adminId = null, array $additionalData = []): bool
    {
        $request = $this->requestRepository->findById($requestId);

        if (!$request) {
            throw new \Exception('Talep bulunamadı.');
        }

        $updateData = ['status' => $status];

        // İlk kez "processing" durumuna geçiriliyorsa admin bilgisini kaydet
        if ($status === 'processing' && !$request->isProcessedByAdmin() && $adminId) {
            $updateData['processed_by'] = $adminId;
            $updateData['processed_at'] = now();
        }

        // Video URL varsa ekle (completed durumunda)
        if (isset($additionalData['video_url'])) {
            $updateData['video_url'] = $additionalData['video_url'];
        }

        // Hata mesajı varsa ekle (failed durumunda)
        if (isset($additionalData['error_message'])) {
            $updateData['error_message'] = $additionalData['error_message'];
        }

        return $this->requestRepository->update($requestId, $updateData);
    }
}
