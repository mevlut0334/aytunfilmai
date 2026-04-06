@extends('layouts.app')

@section('title', __('legal.copyright_title'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h3 class="mb-0">{{ __('legal.copyright_heading') }}</h3>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">{{ __('legal.last_updated') }} {{ date('d.m.Y') }}</p>

                    <div class="alert alert-danger">
                        <h5><i class="bi bi-exclamation-triangle-fill"></i> {{ __('legal.copyright_warning_title') }}</h5>
                        <p class="mb-0">{{ __('legal.copyright_warning_text') }}</p>
                    </div>

                    <h4>{{ __('legal.copyright_1_title') }}</h4>
                    <p>{{ __('legal.copyright_1_text') }}</p>

                    <h4>{{ __('legal.copyright_2_title') }}</h4>
                    <ul>
                        <li>{{ __('legal.copyright_2_1') }}</li>
                        <li>{{ __('legal.copyright_2_2') }}</li>
                        <li>{{ __('legal.copyright_2_3') }}</li>
                        <li>{{ __('legal.copyright_2_4') }}</li>
                    </ul>

                    <h4>{{ __('legal.copyright_3_title') }}</h4>
                    <p>{{ __('legal.copyright_3_text') }}</p>

                    <h4>{{ __('legal.copyright_4_title') }}</h4>
                    <p>{{ __('legal.copyright_4_text') }}</p>

                    <h4>{{ __('legal.copyright_5_title') }}</h4>
                    <p>{{ __('legal.copyright_5_text') }} <a href="mailto:mevluttuncer0334@gmail.com">mevluttuncer0334@gmail.com</a></p>

                    <h4>{{ __('legal.copyright_6_title') }}</h4>
                    <p>{{ __('legal.copyright_6_text') }}</p>

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
