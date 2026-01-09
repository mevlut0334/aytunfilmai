<?php

namespace App\Services\Interfaces;

use App\Models\Request;
use Illuminate\Database\Eloquent\Collection;

interface RequestServiceInterface
{
    /**
     * Talep oluştur (karakterler ve görseller ile)
     *
     * @param int $userId
     * @param array $data
     * @return Request
     */
    public function createRequest(int $userId, array $data): Request;

    /**
     * Kullanıcının taleplerini getir
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserRequests(int $userId): Collection;

    /**
     * Talep detayını getir
     *
     * @param int $requestId
     * @param int $userId (Güvenlik için)
     * @return Request|null
     */
    public function getRequestDetails(int $requestId, int $userId): ?Request;

    /**
     * Talep sil
     *
     * @param int $requestId
     * @param int $userId (Güvenlik için)
     * @return bool
     */
    public function deleteRequest(int $requestId, int $userId): bool;

    /**
     * Tüm talepleri getir (Admin)
     *
     * @param array $filters
     * @return Collection
     */
    public function getAllRequests(array $filters = []): Collection;

    /**
     * Talep durumunu güncelle (Admin)
     *
     * @param int $requestId
     * @param string $status
     * @param array $additionalData (video_url, error_message)
     * @return bool
     */
    public function updateRequestStatus(int $requestId, string $status, ?int $adminId = null, array $additionalData = []): bool;
}
