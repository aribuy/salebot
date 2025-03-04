@extends('backend.layouts.master')
@section('title', __('plans'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex align-items-center justify-content-between mb-12">
                        <h3 class="section-title">{{__('available_plans')}}</h3>
                    </div>
                    <div class="bg-white redious-border p-20 p-sm-30">
                        <div class="row gx-20">
                            @foreach( $packages as $key => $package)
                                <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                                    <div class="package-default mb-4 mb-xl-0">

                                        <div class="package-header package-header-color py-40 px-30 text-center">
                                            <h2 class="package-title">{{ $package->name }}</h2>

                                            <hr style="margin: 12px 0;">

                                            <p>{{ $package->description }}</p>
                                        </div>

                                        <div class="package-content">
                                            <h2 class="package-pirce text-center">{{ get_price($package->price)}}</h2>

                                            <ul>
                                                <li class="d-flex align-items-center justify-content-between py-3 px-30">
                                                    <p>{{__('contacts_limit')}}</p>
                                                    <span>{{ $package->contact_limit }}</span>
                                                </li>
                                                <li class="d-flex align-items-center justify-content-between py-3 px-30">
                                                    <p>{{__('campaigns_limit') }}</p>
                                                    <span>{{ $package->campaigns_limit }}</span>
                                                </li>
                                                <li class="d-flex align-items-center justify-content-between py-3 px-30">
                                                    <p>{{__('team_limit')}}</p>
                                                    <span>{{ $package->team_limit }}</span>
                                                </li>
                                                <li class="d-flex align-items-center justify-content-between py-3 px-30">
                                                    <p>{{__('conversation_limit')}}</p>
                                                    <span>{{ $package->conversation_limit }}</span>
                                                </li>
                                                <li class="d-flex align-items-center justify-content-between py-3 px-30">
                                                    <p>{{__('featured') }}</p>
                                                    @if($package->featured == 1)
                                                        <span>{{__('yes')}}</span>
                                                    @else
                                                        <span>{{__('no')}}</span>
                                                    @endif
                                                </li>
                                                <li class="d-flex align-items-center justify-content-between py-3 px-30">
                                                    <p>{{__('telegram_access') }}</p>
                                                    @if($package->telegram_access == 1)
                                                        <span>{{__('yes')}}</span>
                                                    @else
                                                        <span>{{__('no')}}</span>
                                                    @endif
                                                </li>
                                                <li class="d-flex align-items-center justify-content-between py-3 px-30">
                                                    <p>{{__('billing_period')}}</p>
                                                    <span>{{ ucwords($package->billing_period) }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-center">
                                        @if(@auth()->user()->activeSubscription->plan_id == $package->id)
                                            <a class="btn btn-sm btn-secondary btn-lg disabled">
                                                <span>{{__('current_subscription')}}</span>
                                            </a>
                                        {{-- @elseif ($package->is_free==1) --}}
                                        @else
                                            <a href="{{ route('client.upgrade.plan', $package->id) }}" class="btn sg-btn-primary">
                                                <span>{{__('upgrade')}}</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

