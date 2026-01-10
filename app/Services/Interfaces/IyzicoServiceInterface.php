<?php

namespace App\Services\Interfaces;

interface IyzicoServiceInterface
{
    /**
     * 3D Secure ödeme başlat
     */
    public function initiate3DSecurePayment(array $orderData, array $cardData): array;

    /**
     * 3D Secure callback işle
     */
    public function handle3DSecureCallback(array $callbackData): array;
}
