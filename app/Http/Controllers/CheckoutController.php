<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Paddle ödeme başarılı sayfası.
     * Paddle önce bu sayfayı açar, token yüklemesi webhook ile ayrıca yapılır.
     */
    public function success(): View
    {
        return view('checkout.success');
    }

    /**
     * Paddle ödeme başarısız / iptal sayfası.
     */
    public function fail(): View
    {
        return view('checkout.fail');
    }
}
