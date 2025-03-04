@extends('backend.layouts.master')
@section('title', __('payment_methods'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="section-title">{{ __('payment_methods') }}</h3>
                    <div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
                        <div class="row align-items-center g-20">
                            @include('backend.admin.system_setting.payment_gateways.offline')
                            @include('backend.admin.system_setting.payment_gateways.paypal')
                            @include('backend.admin.system_setting.payment_gateways.stripe')
                            @include('backend.admin.system_setting.payment_gateways.paddle')
                            {{-- @include('backend.admin.system_setting.payment_gateways.razor_pay') --}}
                            {{-- @include('backend.admin.system_setting.payment_gateways.mercadopago') --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
