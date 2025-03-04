@extends('backend.layouts.master')
@section('title', isset($plan) ? __('edit_plan') : __('create_plan'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="section-title">{{ isset($plan) ? __('edit_plan') : __('create_plan') }}</h3>
                    <div class="bg-white redious-border p-20 p-sm-30">
                        @php
                        $route = isset($plan) ? route('plans.update',$plan->id) : route('plans.store');
                        @endphp
                        <form action="{{ $route }}" class="form-validate form"
                              method="POST">
                            @csrf
                            @isset($plan)
                                @method('PUT')
                            @endisset
                            <div class="row gx-20">
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="planName" class="form-label">{{__('plan_name')}}<span
                                                    class="text-danger">*</span></label>
                                        <input type="text" class="form-control rounded-2" id="planName" name="name" value="{{ isset($plan) ? $plan->name : ''}}" placeholder="{{__('plan_name')}}" >
                                        <div class="nk-block-des text-danger">
                                            <p class="name_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Package Name -->

                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="description" class="form-label">{{__('description')}}<span
                                                    class="text-danger">*</span></label>
                                        <textarea class="form-control" name="description"  placeholder="{{__('description')}}" id="description">{{ isset($plan) ? $plan->description : '' }}</textarea>
                                        <div class="nk-block-des text-danger">
                                            <p class="description_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 custom-control custom-checkbox contacts-list">
                                    <div class="mb-2 mt-2">
                                        <label for="is_free" class="custom-control-label  pb-4">
                                        <input type="checkbox" class="custom-control-input read common-key pb-4" name="is_free" value="1" id="is_free" {{ @$plan->is_free==1 ? 'checked':"" }}>
                                        <span>{{__('is_free')}}</span>
                                    </label>
                                        <div class="nk-block-des text-danger">
                                            <p class="is_free_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class=col-lg-3>
                                    <div class="mb-4">
                                        <label for="planPrice" class="form-label">{{__('plan_price')}}<span
                                                    class="text-danger">*</span></label>
                                        <input type="number" class="form-control rounded-2" value="{{ isset($plan) ? priceFormatUpdate($plan->price,'*') : ''}}" id="planPrice" name="price" placeholder="{{__('plan_price')}}" min="0"  step="0.01">
                                        <div class="nk-block-des text-danger">
                                            <p class="price_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Package Price -->

                                <div class="col-lg-3">
                                    <div class="mb-4">
                                        <label for="planValidity" class="form-label">{{__('billing_period')}}<span
                                                    class="text-danger">*</span></label>
                                        <div class="select-type-v2">
                                            <select id="planValidity" name="billing_period" class="form-select form-select-lg mb-3 without_search">
                                                {{-- <option value="daily" @selected(isset($plan) ? $plan->billing_period == 'daily' : '' )>{{__('daily')}}</option> --}}
                                                {{-- <option value="weekly" @selected(isset($plan) ? $plan->billing_period == 'weekly' : '' )>{{__('weekly')}}</option> --}}
                                                <option value="monthly" @selected(isset($plan) ? $plan->billing_period == 'monthly' : '' )>{{__('monthly')}}</option>
                                                {{-- <option value="quarterly" @selected(isset($plan) ? $plan->billing_period == 'quarterly' : '' )>{{__('quarterly')}}</option> --}}
                                                {{-- <option value="half_yearly" @selected(isset($plan) ? $plan->billing_period == 'half_yearly' : '' )>{{__('half_yearly')}}</option> --}}
                                                <option value="yearly" @selected(isset($plan) ? $plan->billing_period == 'yearly' : '' )>{{__('yearly')}}</option>
                                                {{-- <option value="lifetime" @selected(isset($plan) ? $plan->billing_period == 'lifetime' : '' )>{{__('lifetime')}}</option> --}}
                                            </select>
                                            <div class="nk-block-des text-danger">
                                                <p class="billing_period_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Package Validity -->

                                <div class="col-lg-3">
                                    <div class="mb-4">
                                        <label for="contactUploadLimit" class="form-label">{{__('contacts_limit')}}<span
                                                    class="text-danger">*</span></label>
                                        <input type="number" class="form-control rounded-2" id="contactUploadLimit" name="contact_limit" value="{{ isset($plan) ?  $plan->contact_limit : ''}}" placeholder="{{__('contacts_limit')}}" >
                                        <div class="nk-block-des text-danger">
                                            <p class="contact_limit_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Course Upload Limit -->

                                <div class="col-lg-3">
                                    <div class="mb-4">
                                        <label for="campaigns_limit" class="form-label">{{__('campaigns_limit')}}<span
                                                    class="text-danger">*</span></label>
                                        <div class="select-type-v2">
                                            <input type="number" class="form-control rounded-2" id="campaigns_limit" name="campaigns_limit" value="{{isset($plan) ?  $plan->campaigns_limit : ''}}" placeholder="{{__('campaigns_limit')}}" >
                                            <div class="nk-block-des text-danger">
                                                <p class="campaigns_limit_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Course Bundle -->
                                @php
								$chatWidgetActivated = addon_is_activated('chat_widget');
								$chatWidgetRouteExists = Route::has('client.chatwidget.index');
                                @endphp
                                @if($chatWidgetActivated && $chatWidgetRouteExists)
                                    <div class="col-lg-3">
                                        <div class="mb-4">
                                            <label for="max_chatwidget" class="form-label">{{__('max_chatwidget')}}<span
                                                        class="text-danger">*</span></label>
                                            <div class="select-type-v2">
                                                <input type="number" class="form-control rounded-2" id="max_chatwidget"
                                                    name="max_chatwidget"  value="{{isset($plan) ?  $plan->max_chatwidget : ''}}" placeholder="{{__('max_chatwidget')}}" min="0">
                                                <div class="nk-block-des text-danger">
                                                    <p class="max_chatwidget_error error"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-lg-3">
                                    <div class="mb-4">
                                        <label for="conversation_limit" class="form-label">{{__('conversation_limit')}}<span
                                                    class="text-danger">*</span></label>
                                        <input type="number" class="form-control rounded-2" id="conversation_limit" placeholder="{{__('conversation_limit')}}" name="conversation_limit"
                                               value="{{isset($plan) ? $plan->conversation_limit : ''}}">
                                        <div class="nk-block-des text-danger">
                                            <p class="conversation_limit_error error"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="mb-4">
                                        <label for="max_flow_builder" class="form-label">{{__('max_flow_builder')}}<span
                                                    class="text-danger">*</span></label>
                                        <input type="number" class="form-control rounded-2" id="max_flow_builder"
                                               placeholder="{{__('max_flow_builder')}}" name="max_flow_builder" value="{{isset($plan) ? $plan->max_flow_builder : ''}}" min="0">
                                        <div class="nk-block-des text-danger">
                                            <p class="max_flow_builder_error error"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="mb-4">
                                        <label for="max_bot_reply" class="form-label">{{__('max_bot_reply')}}<span
                                                    class="text-danger">*</span></label>
                                        <input type="number" class="form-control rounded-2" id="max_bot_reply"
                                               placeholder="{{__('max_bot_reply')}}" name="max_bot_reply" value="{{isset($plan) ? $plan->max_bot_reply : ''}}" min="0">
                                        <div class="nk-block-des text-danger">
                                            <p class="max_bot_reply_error error"></p>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-3">
                                    <div class="mb-4">
                                        <label for="team_limit" class="form-label">{{__('team_limit')}}<span
                                                    class="text-danger">*</span></label>
                                        <input type="number" class="form-control rounded-2" id="team_limit" name="team_limit" value="{{isset($plan) ? $plan->team_limit : ''}}" placeholder="{{__('team_limit')}}" >
                                        <div class="nk-block-des text-danger">
                                            <p class="team_limit_error error"></p>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-3">
                                    <div class="mb-4">
                                        <label for="color" class="form-label">{{__('color')}}</label>
                                        <input type="color" class="colorpicker form-control rounded-2" id="colorPicker" name="color" value="{{isset($plan) ? $plan->color : '#e0e8f9'}}" placeholder="{{__('color')}}" >
                                        <div class="nk-block-des text-danger">
                                            <p class="color_error error"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 custom-control custom-checkbox contacts-list">
                                    <div class="mb-4">
                                        <label class="custom-control-label  pb-4"
                                               for="telegram_access">
                                            <input type="checkbox"
                                                   class="custom-control-input read common-key pb-4"
                                                   name="telegram_access"
                                                   value="1"
                                                   id="telegram_access" {{ isset($plan) && !$plan->telegram_access ? '' : 'checked' }}>
                                            <span>{{__('telegram_access')}}</span>
                                        </label>
                                    </div>
                                </div>
                                <!-- End Course Upload Limit -->


                                @if(setting('is_stripe_activated') && setting('stripe_secret') && setting('stripe_key'))
                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="stripe_plan_key" class="form-label">{{__('stripe_plan_key')}}<span
                                                        class="text-danger">*</span></label>
                                            <input type="text" value="{{ $stripe_key ?? '' }}" class="form-control rounded-2" id="stripe_plan_key" name="stripe" placeholder="{{__('stripe_plan_key')}}">
                                            <div class="nk-block-des text-danger">
                                                <p class="stripe_plan_key_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(setting('paypal_client_id') && setting('paypal_client_secret') && setting('is_paypal_activated'))
                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="paypal_plan_id" class="form-label">{{__('paypal_plan_id')}}
                                                <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control rounded-2" id="paypal_plan_id"
                                                   name="paypal" placeholder="{{__('paypal_plan_id')}}" value="{{ $paypal ?? '' }}">
                                            <div class="nk-block-des text-danger">
                                                <p class="paypal_plan_id_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(setting('paddle_api_key') && setting('is_paddle_activated'))
                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="paypal_plan_id" class="form-label">{{__('paddle_price_id')}}
                                                <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control rounded-2" id="price_id"
                                                   name="paddle" placeholder="{{__('paddle_price_id')}}" value="{{ $paddle ?? '' }}">
                                            <div class="nk-block-des text-danger">
                                                <p class="paddle_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(setting('razor_pay_key') && setting('razor_pay_secret') && setting('is_razor_pay_activated'))
                                    <div class="col-lg-6">
                                        <div class="mb-4">
                                            <label for="paypal_plan_id" class="form-label">{{ __('razor_pay_plan_id') }}
                                                <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control rounded-2" id="razor_pay_plan_id"
                                                   name="razor_pay" placeholder="{{__('razor_pay_plan_id')}}" value="{{ $razor_pay ?? '' }}">
                                            <div class="nk-block-des text-danger">
                                                <p class="razor_pay_plan_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-lg-3">
                                    <div class="mb-4">
                                        <label for="featured" class="form-label">{{__('featured')}}</label>
                                        <div class="select-type-v2">
                                            <select id="featured" class="form-select form-select-lg mb-3 without_search" name="featured">
                                                <option value="1" @selected(isset($plan) && $plan->featured == '1')>{{__('yes')}}</option>
                                                <option value="0" @selected(isset($plan) && $plan->featured != '1')>{{__('no')}}</option>
                                            </select>
                                            <div class="nk-block-des text-danger">
                                                <p class="featured_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Live Class Facilities -->

                                <div class="col-lg-3">
                                    <div class="mb-4">
                                        <label for="planStatus" class="form-label">{{__('plan_status')}}</label>
                                        <div class="select-type-v2">
                                            <select id="planStatus" class="form-select form-select-lg mb-3 without_search" name="status">
                                                <option @selected(isset($plan) && $plan->status == '1') value="1" selected>{{__('active')}}</option>
                                                <option @selected(isset($plan) && $plan->status != '1') value="0">{{__('inactive')}}</option>
                                            </select>
                                            <div class="nk-block-des text-danger">
                                                <p class="status_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Package Status -->

 
                                <div class="d-flex justify-content-end align-items-center mt-30">
                                    <button type="submit" class="btn sg-btn-primary">{{__('submit') }}</button>
                                    @include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
    
    @push('js')
    <script>
    $(document).ready(function() {
        $('#is_free').change(function() {
            var isFreeChecked = $(this).is(':checked');
            var planPriceInput = $('#planPrice');
            
            if (isFreeChecked) {
                planPriceInput.val('0').prop('readonly', true);
            } else {
                planPriceInput.val('').prop('readonly', false);
            }
        });
    });
    </script>
    @endpush
@endsection

