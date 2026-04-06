@extends('layouts.app')

@section('title', __('legal.personal_title'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">{{ __('legal.personal_heading') }}</h3>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">{{ __('legal.last_updated') }} {{ date('d.m.Y') }}</p>

                    <h4>{{ __('legal.personal_1_title') }}</h4>
                    <p>{{ __('legal.personal_1_text') }}</p>

                    <h4>{{ __('legal.personal_2_title') }}</h4>
                    <ul>
                        <li>{{ __('legal.personal_2_1') }}</li>
                        <li>{{ __('legal.personal_2_2') }}</li>
                        <li>{{ __('legal.personal_2_3') }}</li>
                        <li>{{ __('legal.personal_2_4') }}</li>
                        <li>{{ __('legal.personal_2_5') }}</li>
                    </ul>

                    <h4>{{ __('legal.personal_3_title') }}</h4>
                    <ul>
                        <li><strong>{{ __('legal.personal_3_1') }}</strong></li>
                        <li><strong>{{ __('legal.personal_3_2') }}</strong></li>
                        <li><strong>{{ __('legal.personal_3_3') }}</strong></li>
                        <li><strong>{{ __('legal.personal_3_4') }}</strong></li>
                        <li><strong>{{ __('legal.personal_3_5') }}</strong></li>
                    </ul>

                    <h4>{{ __('legal.personal_4_title') }}</h4>
                    <p>{{ __('legal.personal_4_text') }}</p>

                    <h4>{{ __('legal.personal_5_title') }}</h4>
                    <p>{{ __('legal.personal_5_text') }}</p>
                    <ul>
                        <li>{{ __('legal.personal_5_1') }}</li>
                        <li>{{ __('legal.personal_5_2') }}</li>
                        <li>{{ __('legal.personal_5_3') }}</li>
                        <li>{{ __('legal.personal_5_4') }}</li>
                    </ul>

                    <h4>{{ __('legal.personal_6_title') }}</h4>
                    <p>{{ __('legal.personal_6_text') }}</p>

                    <h4>{{ __('legal.personal_7_title') }}</h4>
                    <p>{{ __('legal.personal_7_text') }} <a href="mailto:mevluttuncer0334@gmail.com">mevluttuncer0334@gmail.com</a></p>

                    <h4>{{ __('legal.personal_8_title') }}</h4>
                    <p>{{ __('legal.personal_8_text') }}</p>

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
