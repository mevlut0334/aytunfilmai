<?php

namespace App\Repositories;

use App\Models\Request;
use App\Models\RequestCharacter;
use App\Models\RequestCharacterImage;
use App\Repositories\Interfaces\RequestRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RequestRepository implements RequestRepositoryInterface
{
    /**
     * Tüm talepleri getir
     * Performans: Eager loading desteği
     *
     * @param array $relations
     * @return Collection
     */
    public function getAll(array $relations = []): Collection
    {
        return Request::with($relations)
            ->latest()
            ->get();
    }

    /**
     * Kullanıcının taleplerini getir
     * Performans: Scope + eager loading
     *
     * @param int $userId
     * @param array $relations
     * @return Collection
     */
    public function getUserRequests(int $userId, array $relations = []): Collection
    {
        return Request::with($relations)
            ->forUser($userId)
            ->latest()
            ->get();
    }

    /**
     * ID ile talep getir
     *
     * @param int $id
     * @param array $relations
     * @return Request|null
     */
    public function findById(int $id, array $relations = []): ?Request
    {
        return Request::with($relations)->find($id);
    }

    /**
     * Talep oluştur
     *
     * @param array $data
     * @return Request
     */
    public function create(array $data): Request
    {
        return Request::create($data);
    }

    /**
     * Talep güncelle
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return Request::where('id', $id)->update($data);
    }

    /**
     * Talep sil
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $request = Request::find($id);
        return $request ? $request->delete() : false;
    }

    /**
     * Talep durumunu güncelle
     * Admin panelinden kullanılacak
     *
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function updateStatus(int $id, string $status): bool
    {
        return Request::where('id', $id)->update(['status' => $status]);
    }

    /**
     * Karakter ekle
     *
     * @param int $requestId
     * @param array $data
     * @return RequestCharacter
     */
    public function addCharacter(int $requestId, array $data)
    {
        return RequestCharacter::create([
            'request_id' => $requestId,
            'name' => $data['name'],
        ]);
    }

    /**
     * Karakter görseli ekle
     *
     * @param int $characterId
     * @param string $imagePath
     * @param int $order
     * @return RequestCharacterImage
     */
    public function addCharacterImage(int $characterId, string $imagePath, int $order)
    {
        return RequestCharacterImage::create([
            'character_id' => $characterId,
            'image_path' => $imagePath,
            'order' => $order,
        ]);
    }

    /**
     * Duruma göre talepleri getir
     * Admin paneli için filtreleme
     *
     * @param string $status
     * @param array $relations
     * @return Collection
     */
    public function getByStatus(string $status, array $relations = []): Collection
    {
        return Request::with($relations)
            ->where('status', $status)
            ->latest()
            ->get();
    }
}
