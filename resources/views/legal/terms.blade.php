@extends('layouts.app')

@section('title', __('legal.terms_title'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">{{ __('legal.terms_heading') }}</h3>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">{{ __('legal.last_updated') }} {{ date('d.m.Y') }}</p>

                    <h4>{{ __('legal.terms_1_title') }}</h4>
                    <p>{{ __('legal.terms_1_text') }}</p>

                    <h4>{{ __('legal.terms_2_title') }}</h4>
                    <p>{{ __('legal.terms_2_text') }}</p>

                    <h4>{{ __('legal.terms_3_title') }}</h4>
                    <ul>
                        <li>{{ __('legal.terms_3_1') }}</li>
                        <li>{{ __('legal.terms_3_2') }}</li>
                        <li>{{ __('legal.terms_3_3') }}</li>
                    </ul>

                    <h4>{{ __('legal.terms_4_title') }}</h4>
                    <ul>
                        <li>{{ __('legal.terms_4_1') }}</li>
                        <li>{{ __('legal.terms_4_2') }}</li>
                        <li>{{ __('legal.terms_4_3') }}</li>
                    </ul>

                    <h4>{{ __('legal.terms_5_title') }}</h4>
                    <ul>
                        <li>{{ __('legal.terms_5_1') }}</li>
                        <li>{{ __('legal.terms_5_2') }}</li>
                        <li>{{ __('legal.terms_5_3') }}</li>
                    </ul>

                    <h4>{{ __('legal.terms_6_title') }}</h4>
                    <p>{{ __('legal.terms_6_text') }}</p>

                    <h4>{{ __('legal.terms_7_title') }}</h4>
                    <p>{{ __('legal.terms_7_text') }}</p>

                    <h4>{{ __('legal.terms_8_title') }}</h4>
                    <p>{{ __('legal.terms_8_text') }} <a href="mailto:mevluttuncer0334@gmail.com">mevluttuncer0334@gmail.com</a></p>

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
