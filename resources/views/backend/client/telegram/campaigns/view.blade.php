@extends('backend.layouts.master')
@section('title', __('campaigns'))
@section('content')
    <section class="oftions">
        <div class="row mb-20">
            <div class="col-lg-12">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="d-flex justify-content-between">
                                <h3 class="section-title">{{ @$campaign->campaign_name }}</h3>
                            </div>
                            <?php
                            $client = Auth::user()->client;
                            $activeContactsCount = $client->contacts()->active()->count();
                            $campaign_contact = $campaign->total_contact ?? 0;
                            $campaign_contact_percent = $campaign_contact != 0 ? ($campaign_contact / $activeContactsCount) * 100 : 0;
                            $total_delivered = $campaign->total_delivered;
                            $total_delivered_percent = $total_delivered != 0 ? ($total_delivered / $campaign_contact) * 100 : 0;
                            $total_read = $campaign->total_read;
                            $read_percent = $total_read != 0 ? ($total_read / $campaign_contact) * 100 : 0;
                            ?>
                            <div class="redious-border mb-40 p-5 p-sm-20 bg-white" style="position: relative;">
                                <span
                                    class="@if (@$campaign->campaign_type->value == \App\Enums\TypeEnum::WHATSAPP->value) whatsapp-badge @else telegram-badge @endif">{{ $campaign->campaign_name }}
                                    - @if (@$campaign->campaign_type->value == \App\Enums\TypeEnum::WHATSAPP->value)
                                        {{ __('whatsapp') }}
                                    @else
                                        {{ __('telegram') }}
                                    @endif
                                </span>
                                <div class="mt-4"></div>
                                <div class="row">

                                    <div class="col-xxl-3 col-lg-6 col-md-6 mb-20 mb-xxl-0">
                                        <div class="bg-white redious-border p-20 p-sm-30">
                                            <div class="analytics clr-2">
                                                <div class="analytics-icon">
                                                    <i class="lar la-user"></i>
                                                </div>
                                                <div class="analytics-content">
                                                    <h4>{{ $campaign_contact }}</h4>
                                                    <p>{{ __('contacts') }}</p>
                                                    <div>
                                                        {{ number_format($campaign_contact_percent, 0) }}%
                                                        {{ __('of_your_contacts') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3 col-lg-6 col-md-6 mb-20 mb-lg-0">
                                        <div class="bg-white redious-border p-20 p-sm-30">
                                            <div class="analytics clr-3">
                                                <div class="analytics-icon">
                                                    <i class="las la-check-circle"></i>
                                                </div>
                                                <div class="analytics-content">
                                                    <h4>{{ number_format($total_delivered_percent, 0) }} %</h4>
                                                    <p>{{ __('delivered_to') }}</p>
                                                    <div>
                                                        {{ $campaign_contact }} {{ __('contacts') }}
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <div class="row mt-4 mb-4">
                                    <div class="col-12">
                                        <div
                                            class="default-tab-list default-tab-list-v2  bg-white redious-border p-20 p-sm-30">
                                            <ul class="nav pb-12 mb-20" id="pills-tab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link active ps-0" id="allConversation"
                                                        data-bs-toggle="pill" data-bs-target="#all" role="tab"
                                                        aria-controls="all" aria-selected="true">{{ __('all') }}</a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" id="deliveredConversation" data-bs-toggle="pill"
                                                        data-bs-target="#delivered" role="tab" aria-controls="delivered"
                                                        aria-selected="false">{{ __('delivered') }}</a>
                                                </li>

                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" id="failedConversation" data-bs-toggle="pill"
                                                        data-bs-target="#failed" role="tab" aria-controls="failed"
                                                        aria-selected="false">
                                                        {{ __('failed') }}
                                                    </a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" id="scheduledConversation" data-bs-toggle="pill"
                                                        data-bs-target="#scheduled" role="tab" aria-controls="scheduled"
                                                        aria-selected="false">
                                                        {{ __('scheduled') }}
                                                    </a>
                                                </li>
                                            </ul>

                                            <div class="tab-content" id="pills-tabContent">
                                                <div class="tab-pane fade show active" id="all" role="tabpanel"
                                                    aria-labelledby="courses" tabindex="0">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table class="table table-bordered1">
                                                                <tr>
                                                                    <th>{{ __('name') }}</th>
                                                                    <th>{{ __('scheduled_at') }}</th>
                                                                    <th>{{ __('status') }}</th>
                                                                </tr>
                                                                @if (!empty(@$campaign->conversation))
                                                                    @foreach (@$campaign->conversation as $conversation)
                                                                        <tr>
                                                                            <td>{{ @$conversation->contact->name }}</td>
                                                                            <td>{{ date('d M Y H:i:s', strtotime($conversation->schedule_at)) }}
                                                                            </td>
                                                                            <td class="text-capitalize">
                                                                                @if ($conversation->status==\App\Enums\MessageStatusEnum::SCHEDULED)
                                                                                <span class="badge rounded-pill bg-success text-capitalize bg-opacity-75" style="line-height: 15px!important;">
                                                                                    {{ $conversation->status }}
                                                                                </span>
                                                                                @elseif ($conversation->status==\App\Enums\MessageStatusEnum::DELIVERED)
                                                                                <span class="badge rounded-pill bg-info text-capitalize bg-opacity-75" style="line-height: 15px!important;">
                                                                                    {{ $conversation->status }}
                                                                                </span>
                                                                                @elseif ($conversation->status==\App\Enums\MessageStatusEnum::READ)
                                                                                <span class="badge rounded-pill bg-primary text-capitalize bg-opacity-75" style="line-height: 15px!important;">
                                                                                    {{ $conversation->status }}
                                                                                </span>
                                                                                @elseif ($conversation->status==\App\Enums\MessageStatusEnum::FAILED)
                                                                                <span class="badge rounded-pill bg-danger text-capitalize bg-opacity-75" style="line-height: 15px!important;">
                                                                                    {{ $conversation->status }}
                                                                                </span>
                                                                                @else
                                                                                <span class="badge rounded-pill bg-secondary text-capitalize bg-opacity-75" style="line-height: 15px!important;">
                                                                                    {{ $conversation->status }}
                                                                                </span>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="delivered" role="tabpanel"
                                                    aria-labelledby="delivered" tabindex="0">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <table class="table table-bordered1">
                                                                <tr>
                                                                    <th>{{ __('name') }}</th>
                                                                    <th>{{ __('scheduled_at') }}</th>
                                                                    <th>{{ __('status') }}</th>
                                                                </tr>
                                                                @if (!empty(@$campaign->conversation->where('status', \App\Enums\MessageStatusEnum::DELIVERED)))
                                                                    @foreach (@$campaign->conversation->where('status', \App\Enums\MessageStatusEnum::DELIVERED) as $conversation)
                                                                        <tr>
                                                                            <td>{{ @$conversation->contact->name }}</td>
                                                                            <td>{{ date('d M Y H:i:s', strtotime($conversation->schedule_at)) }}
                                                                            </td>
                                                                            <td class="text-capitalize">
                                                                                @if ($conversation->status==\App\Enums\MessageStatusEnum::SCHEDULED)
                                                                                    <span class="badge rounded-pill bg-success text-capitalize bg-opacity-75" style="line-height: 15px!important;">
                                                                                        {{ $conversation->status }}
                                                                                    </span>
                                                                                    @elseif ($conversation->status==\App\Enums\MessageStatusEnum::DELIVERED)
                                                                                    <span class="badge rounded-pill bg-info text-capitalize bg-opacity-75" style="line-height: 15px!important;">
                                                                                        {{ $conversation->status }}
                                                                                    </span>
                                                                                    @elseif ($conversation->status==\App\Enums\MessageStatusEnum::READ)
                                                                                    <span class="badge rounded-pill bg-primary text-capitalize bg-opacity-75" style="line-height: 15px!important;">
                                                                                        {{ $conversation->status }}
                                                                                    </span>
                                                                                    @elseif ($conversation->status==\App\Enums\MessageStatusEnum::FAILED)
                                                                                    <span class="badge rounded-pill bg-danger text-capitalize bg-opacity-75" style="line-height: 15px!important;">
                                                                                        {{ $conversation->status }}
                                                                                    </span>
                                                                                    @else
                                                                                    <span class="badge rounded-pill bg-secondary text-capitalize bg-opacity-75" style="line-height: 15px!important;">
                                                                                        {{ $conversation->status }}
                                                                                    </span>
                                                                                    @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-pane fade" id="failed" role="tabpanel"
                                                    aria-labelledby="failed" tabindex="0">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <table class="table table-bordered1">
                                                                <tr>
                                                                    <th>{{ __('name') }}</th>
                                                                    <th>{{ __('scheduled_at') }}</th>
                                                                    <th>{{ __('status') }}</th>
                                                                </tr>
                                                                @if (!empty(@$campaign->conversation->where('status', \App\Enums\MessageStatusEnum::FAILED)))
                                                                    @foreach (@$campaign->conversation->where('status', \App\Enums\MessageStatusEnum::FAILED) as $conversation)
                                                                        <tr>
                                                                            <td>{{ @$conversation->contact->name }}</td>
                                                                            <td>{{ date('d M Y H:i:s', strtotime($conversation->schedule_at)) }}
                                                                            </td>
                                                                            <td class="text-capitalize">
                                                                                @if ($conversation->status==\App\Enums\MessageStatusEnum::SCHEDULED)
                                                                                <span class="badge rounded-pill bg-success text-capitalize bg-opacity-75" style="line-height: 15px!important;">
                                                                                    {{ $conversation->status }}
                                                                                </span>
                                                                                @elseif ($conversation->status==\App\Enums\MessageStatusEnum::DELIVERED)
                                                                                <span class="badge rounded-pill bg-info text-capitalize bg-opacity-75" style="line-height: 15px!important;">
                                                                                    {{ $conversation->status }}
                                                                                </span>
                                                                                @elseif ($conversation->status==\App\Enums\MessageStatusEnum::READ)
                                                                                <span class="badge rounded-pill bg-primary text-capitalize bg-opacity-75" style="line-height: 15px!important;">
                                                                                    {{ $conversation->status }}
                                                                                </span>
                                                                                @elseif ($conversation->status==\App\Enums\MessageStatusEnum::FAILED)
                                                                                <span class="badge rounded-pill bg-danger text-capitalize bg-opacity-75" style="line-height: 15px!important;">
                                                                                    {{ $conversation->status }}
                                                                                </span>
                                                                                @else
                                                                                <span class="badge rounded-pill bg-secondary text-capitalize bg-opacity-75" style="line-height: 15px!important;">
                                                                                    {{ $conversation->status }}
                                                                                </span>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="scheduled" role="tabpanel"
                                                    aria-labelledby="scheduled" tabindex="0">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <table class="table table-bordered1">
                                                                <tr>
                                                                    <th>{{ __('name') }}</th>
                                                                    <th>{{ __('scheduled_at') }}</th>
                                                                    <th>{{ __('status') }}</th>
                                                                </tr>
                                                                @if (!empty(@$campaign->conversation->where('status', \App\Enums\MessageStatusEnum::SCHEDULED)))
                                                                    @foreach (@$campaign->conversation->where('status', \App\Enums\MessageStatusEnum::SCHEDULED) as $conversation)
                                                                        <tr>
                                                                            <td>{{ @$conversation->contact->name }}</td>
                                                                            <td>{{ date('d M Y H:i:s', strtotime($conversation->schedule_at)) }}
                                                                            </td>
                                                                            <td class="text-capitalize">
                                                                                @if ($conversation->status==\App\Enums\MessageStatusEnum::SCHEDULED)
                                                                                <span class="badge rounded-pill bg-success text-capitalize bg-opacity-75" style="line-height: 15px!important;">
                                                                                    {{ $conversation->status }}
                                                                                </span>
                                                                                @elseif ($conversation->status==\App\Enums\MessageStatusEnum::DELIVERED)
                                                                                <span class="badge rounded-pill bg-info text-capitalize bg-opacity-75" style="line-height: 15px!important;">
                                                                                    {{ $conversation->status }}
                                                                                </span>
                                                                                @elseif ($conversation->status==\App\Enums\MessageStatusEnum::READ)
                                                                                <span class="badge rounded-pill bg-primary text-capitalize bg-opacity-75" style="line-height: 15px!important;">
                                                                                    {{ $conversation->status }}
                                                                                </span>
                                                                                @elseif ($conversation->status==\App\Enums\MessageStatusEnum::FAILED)
                                                                                <span class="badge rounded-pill bg-danger text-capitalize bg-opacity-75" style="line-height: 15px!important;">
                                                                                    {{ $conversation->status }}
                                                                                </span>
                                                                                @else
                                                                                <span class="badge rounded-pill bg-secondary text-capitalize bg-opacity-75" style="line-height: 15px!important;">
                                                                                    {{ $conversation->status }}
                                                                                </span>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
