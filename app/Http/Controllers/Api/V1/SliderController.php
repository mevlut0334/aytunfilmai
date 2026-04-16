<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\MobileSlider;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    use ApiResponseTrait;

    public function index(): JsonResponse
    {
        $sliders = MobileSlider::active()
            ->ordered()
            ->get()
            ->map(function ($slider) {
                return [
                    'id'        => $slider->id,
                    'image_url' => Storage::url($slider->image),
                    'link'      => $slider->link,
                    'order'     => $slider->order,
                ];
            });

        return $this->success($sliders, 'Sliderlar başarıyla getirildi.');
    }
}
