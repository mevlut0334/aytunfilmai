<?php

namespace App\Repositories\Interfaces;

use App\Models\Request;
use Illuminate\Database\Eloquent\Collection;

interface RequestRepositoryInterface
{
    /**
     * Tüm talepleri getir
     *
     * @param array $relations
     * @return Collection
     */
    public function getAll(array $relations = []): Collection;

    /**
     * Kullanıcının taleplerini getir
     *
     * @param int $userId
     * @param array $relations
     * @return Collection
     */
    public function getUserRequests(int $userId, array $relations = []): Collection;

    /**
     * ID ile talep getir
     *
     * @param int $id
     * @param array $relations
     * @return Request|null
     */
    public function findById(int $id, array $relations = []): ?Request;

    /**
     * Talep oluştur
     *
     * @param array $data
     * @return Request
     */
    public function create(array $data): Request;

    /**
     * Talep güncelle
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Talep sil
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Talep durumunu güncelle
     *
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function updateStatus(int $id, string $status): bool;

    /**
     * Karakter ekle
     *
     * @param int $requestId
     * @param array $data
     * @return \App\Models\RequestCharacter
     */
    public function addCharacter(int $requestId, array $data);

    /**
     * Karakter görseli ekle
     *
     * @param int $characterId
     * @param string $imagePath
     * @param int $order
     * @return \App\Models\RequestCharacterImage
     */
    public function addCharacterImage(int $characterId, string $imagePath, int $order);

    /**
     * Duruma göre talepleri getir
     *
     * @param string $status
     * @param array $relations
     * @return Collection
     */
    public function getByStatus(string $status, array $relations = []): Collection;
}
