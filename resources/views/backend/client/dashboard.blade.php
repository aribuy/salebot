@extends('backend.layouts.master')
@section('title', __('dashboard'))
@push('css')      
    <link rel="stylesheet" href="{{ static_asset('admin/css/dropzone.min.css') }}">
@endpush
@section('content')
    <section class="oftions">
        <div class="container-fluid">    
            <div class="row">
                <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-4">
                    <div class="bg-white redious-border mb-4 p-20 p-sm-30">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="analytics-content mb-1">
                                    <h4>{{__('hello')}} {{Auth()->user()->first_name }},</h4>
                                    <p>{{__('empower_your_business_with')}} {{ setting('system_name') }}</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="analytics clr-1">
                                    <div class="analytics-icon">
                                        <i class="las la-check-double"></i>
                                    </div>
                                    @if($active_subscription)
                                        <div class="analytics-content">
                                            <h4>{{ @$active_subscription->plan->name }}</h4>
                                            <p>{{__('next_billing')}} : {{ Carbon\Carbon::parse($active_subscription->expire_date)->format('Y-m-d') }}</p>
                                        </div>
                                    @else
                                        <div class="analytics-content">
                                            <h4>{{__('no_active_plan')}}</h4>
                                            <p>{{__('next_billing')}}: </p>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-center">
                                <a href="{{ route('client.my.subscription') }}" class="btn btn-sm btn-primary gap-1  mt-20 mb-20">
                                    <span>{{__('manage_subscription')}}</span>
                                </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-9 col-xl-8 col-lg-8 col-md-8">
                    <div class="row">
                        <div class="col-xxl-4 col-xl-4 col-md-6 col-sm-6">
                            <div class="bg-white redious-border mb-4 p-20 p-sm-30 analytics-box">
                                <div class="analytics clr-1">
                                    <div class="analytics-icon">
                                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g id="uil:users-alt">
                                                <path id="Vector" d="M16.4026 16.2933C17.114 15.6775 17.6847 14.9158 18.0758 14.06C18.4669 13.2042 18.6693 12.2743 18.6693 11.3333C18.6693 9.56522 17.9669 7.86953 16.7167 6.61929C15.4664 5.36904 13.7707 4.66666 12.0026 4.66666C10.2345 4.66666 8.5388 5.36904 7.28856 6.61929C6.03832 7.86953 5.33594 9.56522 5.33594 11.3333C5.33593 12.2743 5.53834 13.2042 5.92944 14.06C6.32054 14.9158 6.89117 15.6775 7.6026 16.2933C5.73612 17.1385 4.15256 18.5034 3.04124 20.2247C1.92993 21.9461 1.3379 23.9511 1.33594 26C1.33594 26.3536 1.47641 26.6928 1.72646 26.9428C1.97651 27.1929 2.31565 27.3333 2.66927 27.3333C3.02289 27.3333 3.36203 27.1929 3.61208 26.9428C3.86213 26.6928 4.0026 26.3536 4.0026 26C4.0026 23.8783 4.84546 21.8434 6.34575 20.3431C7.84604 18.8429 9.88087 18 12.0026 18C14.1243 18 16.1592 18.8429 17.6595 20.3431C19.1597 21.8434 20.0026 23.8783 20.0026 26C20.0026 26.3536 20.1431 26.6928 20.3931 26.9428C20.6432 27.1929 20.9823 27.3333 21.3359 27.3333C21.6896 27.3333 22.0287 27.1929 22.2787 26.9428C22.5288 26.6928 22.6693 26.3536 22.6693 26C22.6673 23.9511 22.0753 21.9461 20.964 20.2247C19.8527 18.5034 18.2691 17.1385 16.4026 16.2933ZM12.0026 15.3333C11.2115 15.3333 10.4381 15.0987 9.78032 14.6592C9.12253 14.2197 8.60984 13.595 8.30709 12.8641C8.00434 12.1332 7.92512 11.3289 8.07946 10.553C8.2338 9.77705 8.61477 9.06431 9.17418 8.5049C9.73359 7.94549 10.4463 7.56453 11.2222 7.41019C11.9982 7.25585 12.8024 7.33506 13.5333 7.63781C14.2642 7.94056 14.889 8.45325 15.3285 9.11105C15.768 9.76885 16.0026 10.5422 16.0026 11.3333C16.0026 12.3942 15.5812 13.4116 14.831 14.1618C14.0809 14.9119 13.0635 15.3333 12.0026 15.3333ZM24.9893 15.76C25.8426 14.7991 26.4 13.6121 26.5943 12.3418C26.7887 11.0715 26.6118 9.7721 26.085 8.6C25.5581 7.4279 24.7037 6.43306 23.6246 5.73523C22.5455 5.0374 21.2877 4.66632 20.0026 4.66666C19.649 4.66666 19.3098 4.80714 19.0598 5.05719C18.8097 5.30724 18.6693 5.64638 18.6693 6C18.6693 6.35362 18.8097 6.69276 19.0598 6.94281C19.3098 7.19285 19.649 7.33333 20.0026 7.33333C21.0635 7.33333 22.0809 7.75476 22.831 8.5049C23.5812 9.25505 24.0026 10.2725 24.0026 11.3333C24.0007 12.0336 23.815 12.7212 23.464 13.3272C23.113 13.9332 22.6091 14.4365 22.0026 14.7867C21.8049 14.9007 21.6398 15.0635 21.5231 15.2597C21.4064 15.4558 21.3419 15.6785 21.3359 15.9067C21.3304 16.133 21.3825 16.3571 21.4875 16.5577C21.5925 16.7583 21.7468 16.9289 21.9359 17.0533L22.4559 17.4L22.6293 17.4933C24.2365 18.2556 25.5924 19.4613 26.5372 20.9684C27.4821 22.4755 27.9767 24.2212 27.9626 26C27.9626 26.3536 28.1031 26.6928 28.3531 26.9428C28.6032 27.1929 28.9423 27.3333 29.2959 27.3333C29.6496 27.3333 29.9887 27.1929 30.2387 26.9428C30.4888 26.6928 30.6293 26.3536 30.6293 26C30.6402 23.9539 30.1277 21.939 29.1406 20.1467C28.1534 18.3545 26.7244 16.8444 24.9893 15.76Z" fill="#3F52E3"></path>
                                            </g>
                                        </svg>
                                    </div>
                                    <div class="analytics-content">
                                        <h4>{{$usages['team']}}/{{$active_subscription->team_limit}}</h4>
                                        <p>{{__('team_member')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-xl-4 col-md-6 col-sm-6">
                            <div class="bg-white redious-border mb-4 p-20 p-sm-30 analytics-box">
                                <div class="analytics clr-2">
                                    <div class="analytics-icon">
                                        <i class="las la-bullhorn"></i>
                                    </div>

                                    <div class="analytics-content">
                                        <h4>{{$usages['campaign']}}/{{$active_subscription->campaign_limit}}</h4>
                                        <p>{{__('total_campaign')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4 col-xl-4 col-md-6 col-sm-6">
                            <div class="bg-white redious-border mb-4 p-20 p-sm-30 analytics-box">
                                <div class="analytics clr-3">
                                    <div class="analytics-icon">
                                        <i class="las la-address-book"></i>
                                    </div>

                                    <div class="analytics-content">
                                        <h4>{{ReadableNumbers::make($usages['contact'])}}/{{ReadableNumbers::make($active_subscription->contact_limit)}}</h4>
                                        <p>{{__('total_contacts')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-xl-6 col-md-6 col-sm-6">
                            <div class="bg-white redious-border mb-4 p-20 p-sm-30 analytics-box">
                                <div class="analytics clr-4">
                                    <div class="analytics-icon">
                                        <i class="las la-sms"></i>
                                    </div>
                                    <div class="analytics-content">
                                        <h4>
                                            {{ $usages['conversation'] ? ReadableNumbers::make($usages['conversation']) : 'N/A' }}
                                            /
                                            {{ $active_subscription->conversation_limit ? ReadableNumbers::make($active_subscription->conversation_limit) : 'N/A' }}
                                        </h4>                                        
                                        <p>{{__('total_conversation')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-6 col-xl-6 col-md-12 col-sm-12">
                            <div class="bg-white redious-border mb-4 p-20 p-sm-30 analytics-box">
                                <div class="analytics clr-1">
                                    <div class="analytics-icon">
                                        <i class="las la-envelope-open"></i>
                                    </div>

                                    <div class="analytics-content">
                                        <h4>{{number_format( $read_rate )}}%</h4>
                                        <p>{{__('read_rate')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-md-6">
                    <div class="bg-white redious-border mb-4 pt-20 p-30">
                        <div class="section-top">
                            <h4>{{ __('audience_growth') }}</h4>
                        </div>
                        <div class="statistics-report">
                            <div class="row">
                            </div>
                        </div>
                        <div class="statistics-report-chart">
                            <canvas id="audience_growth"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-md-6">
                    <div class="bg-white redious-border mb-4 pt-20 p-30">
                        <div class="section-top">
                            <h4>{{ __('campaign_statistic') }}</h4>
                        </div>
                        <div class="statistics-report">
                            <div class="row">
                            </div>
                        </div>
                        <div class="statistics-report-chart">
                            <canvas id="campaign_statistic"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="bg-white redious-border mb-4 p-20 p-sm-30">
                        <div class="section-top">
                            <h4>{{__('conversation_statistic')}}</h4>
                        </div>
                        <div class="statistics-report-chart">
                            <canvas id="conversation_statistic"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <input type="hidden" id="chart_data" value="{{ json_encode($charts) }}">
@endsection
@push('js')
    <script src="{{ static_asset('admin/js/chart.min.js') }}"></script>
@endpush
@push('js')
    <script src="{{ static_asset('admin\js\custom\dashboard\client_dashboard_chart.js') }}"></script>
@endpush
