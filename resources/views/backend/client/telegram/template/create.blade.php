@extends('backend.layouts.master')
@section('title', __('templates'))
@section('content')
    <div class="main-content-wrapper">
        <section class="oftions">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <h3 class="section-title">{{__('add_template') }}</h3>
                        <form action="{{ route('client.template.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="bg-white redious-border p-20 p-sm-30">
                            <div class="row gx-20 add-coupon">
                                <input type="hidden" class="is_modal" value="0"/>
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label for="name" class="form-label">{{ __('name') }}<span
                                                    class="text-danger">*</span></label>
                                        <input type="text" class="form-control rounded-2" id="name" name="name"
                                               placeholder="{{ __('name') }}">
                                        @if ($errors->has('name'))
                                            <div class="nk-block-des text-danger">
                                                <p>{{ $errors->first('name') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <div class="select-type-v2">
                                            <label for="locale_id"
                                                   class="form-label">{{ __('locale') }}<span
                                                        class="text-danger">*</span></label>
                                            <select id="locale_id" name="locale_id"
                                                    class="multiple-select-1 form-select-lg rounded-0 mb-3"
                                                    aria-label=".form-select-lg example">
                                                <option value="" selected>{{ __('locale') }}</option>
                                               
                                            </select>
                                            <div class="nk-block-des text-danger">
                                                <p class="error">{{ $errors->first('locale_id') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="phone" class="form-label">{{ __('phone') }}<span
                                                    class="text-danger">*</span></label>
                                        <input type="number" class="form-control rounded-2" id="phone" name="phone"
                                               placeholder="{{ __('phone') }}">
                                        @if ($errors->has('phone'))
                                            <div class="nk-block-des text-danger">
                                                <p>{{ $errors->first('phone') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <div class="select-type-v2">
                                            <label for="select_segments"
                                                   class="form-label">{{ __('segments') }}<span
                                                        class="text-danger">*</span></label>
                                            <select id="select_segments" name="segments"
                                                    class="multiple-select-1 form-select-lg rounded-0 mb-3"
                                                    aria-label=".form-select-lg example">
                                                <option value="" selected>{{ __('select_segments') }}</option>
                                               
                                            </select>
                                            <div class="nk-block-des text-danger">
                                                <p class="error">{{ $errors->first('segments_id') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end align-items-center mt-30">
                                    <button type="submit" class="btn sg-btn-primary">{{__('submit') }}</button>
                                    @include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
                                </div>
                            </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

