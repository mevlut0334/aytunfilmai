@extends('layouts.app')

@section('title', __('legal.kvkk_title'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h3 class="mb-0">{{ __('legal.kvkk_heading') }}</h3>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">{{ __('legal.last_updated') }} {{ date('d.m.Y') }}</p>

                    <h4>{{ __('legal.kvkk_1_title') }}</h4>
                    <p><strong>Aytun Film AI</strong> {{ __('legal.kvkk_1_text') }}</p>

                    <h4>{{ __('legal.kvkk_2_title') }}</h4>
                    <ul>
                        <li><strong>{{ __('legal.kvkk_2_1') }}</strong></li>
                        <li><strong>{{ __('legal.kvkk_2_2') }}</strong></li>
                        <li><strong>{{ __('legal.kvkk_2_3') }}</strong></li>
                        <li><strong>{{ __('legal.kvkk_2_4') }}</strong></li>
                        <li><strong>{{ __('legal.kvkk_2_5') }}</strong></li>
                    </ul>

                    <h4>{{ __('legal.kvkk_3_title') }}</h4>
                    <ul>
                        <li>{{ __('legal.kvkk_3_1') }}</li>
                        <li>{{ __('legal.kvkk_3_2') }}</li>
                        <li>{{ __('legal.kvkk_3_3') }}</li>
                        <li>{{ __('legal.kvkk_3_4') }}</li>
                        <li>{{ __('legal.kvkk_3_5') }}</li>
                    </ul>

                    <h4>{{ __('legal.kvkk_4_title') }}</h4>
                    <p>{{ __('legal.kvkk_4_text') }}</p>

                    <h4>{{ __('legal.kvkk_5_title') }}</h4>
                    <p>{{ __('legal.kvkk_5_text') }}</p>

                    <h4>{{ __('legal.kvkk_6_title') }}</h4>
                    <ul>
                        <li>{{ __('legal.kvkk_6_1') }}</li>
                        <li>{{ __('legal.kvkk_6_2') }}</li>
                        <li>{{ __('legal.kvkk_6_3') }}</li>
                        <li>{{ __('legal.kvkk_6_4') }}</li>
                        <li>{{ __('legal.kvkk_6_5') }}</li>
                        <li>{{ __('legal.kvkk_6_6') }}</li>
                        <li>{{ __('legal.kvkk_6_7') }}</li>
                        <li>{{ __('legal.kvkk_6_8') }}</li>
                        <li>{{ __('legal.kvkk_6_9') }}</li>
                    </ul>

                    <h4>{{ __('legal.kvkk_7_title') }}</h4>
                    <p>{{ __('legal.kvkk_7_text') }} <a href="mailto:mevluttuncer0334@gmail.com">mevluttuncer0334@gmail.com</a></p>

                    <div class="mt-4">
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left"></i> {{ __('legal.back_to_register') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
