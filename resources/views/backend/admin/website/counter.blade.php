@extends('backend.layouts.master')
@section('title', __('cta'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                @include('backend.admin.website.sidebar_component')
                <div class="col-xxl-9 col-lg-8 col-md-8">
                    <h3 class="section-title">{{ __('counter') }}</h3>
                    <div class="bg-white redious-border p-20 p-sm-30">
                        <form action="{{ route('admin.theme.counter.update') }}" method="POST" class="form" enctype="multipart/form-data">@csrf
                            <div class="row gx-20">
                                <div class="col-12 col-lg-5">
                                    <div class="mb-4">
                                        <label for="title" class="form-label">{{ __('counter1_title') }}</label>
                                        <input type="text" class="form-control rounded-2" id="title"
                                                name="counter1_title" value="{{ setting('counter1_title',$lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="ctatitle_error error">{{ $errors->first('counter1_title') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-2">
                                    <div class="mb-4">
                                        <label for="counter1_unit" class="form-label">{{ __('counter1_unit') }}</label>
                                        <input type="text" class="form-control rounded-2" id="counter1_unit"
                                                name="counter1_unit" value="{{ setting('counter1_unit',$lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="counter1_unit_error error">{{ $errors->first('counter1_unit') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-5">
                                    <div class="mb-4">
                                        <label for="counter1_value" class="form-label">{{ __('counter1_value') }}</label>
                                        <input type="number" class="form-control rounded-2" id="counter1_value"
                                                name="counter1_value" value="{{ setting('counter1_value',$lang) }}" step="any">
                                        <div class="nk-block-des text-danger">
                                            <p class="counter1_value_error error">{{ $errors->first('counter1_value') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-5">
                                    <div class="mb-4">
                                        <label for="title" class="form-label">{{ __('counter2_title') }}</label>
                                        <input type="text" class="form-control rounded-2" id="title"
                                                name="counter2_title" value="{{ setting('counter2_title',$lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="counter2_title_error error">{{ $errors->first('counter2_title') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-2">
                                    <div class="mb-4">
                                        <label for="counter2_unit" class="form-label">{{ __('counter2_unit') }}</label>
                                        <input type="text" class="form-control rounded-2" id="counter2_unit"
                                                name="counter2_unit" value="{{ setting('counter2_unit',$lang) }}" >
                                        <div class="nk-block-des text-danger">
                                            <p class="counter2_unit_error error">{{ $errors->first('counter2_unit') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-5">
                                    <div class="mb-4">
                                        <label for="counter2_value" class="form-label">{{ __('counter2_value') }}</label>
                                        <input type="number" class="form-control rounded-2" id="counter2_value"
                                                name="counter2_value" value="{{ setting('counter2_value',$lang) }}" step="any">
                                        <div class="nk-block-des text-danger">
                                            <p class="counter2_value_error error">{{ $errors->first('counter2_value') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-5">
                                    <div class="mb-4">
                                        <label for="title" class="form-label">{{ __('counter3_title') }}</label>
                                        <input type="text" class="form-control rounded-2" id="title"
                                                name="counter3_title" value="{{ setting('counter3_title',$lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="ctatitle_error error">{{ $errors->first('counter3_title') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-2">
                                    <div class="mb-4">
                                        <label for="counter3_unit" class="form-label">{{ __('counter3_unit') }}</label>
                                        <input type="text" class="form-control rounded-2" id="counter3_unit"
                                                name="counter3_unit" value="{{ setting('counter3_unit',$lang) }}">
                                        <div class="nk-block-des text-danger">
                                            <p class="counter3_unit_error error">{{ $errors->first('counter3_unit') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-5">
                                    <div class="mb-4">
                                        <label for="counter3_value" class="form-label">{{ __('counter3_value') }}</label>
                                        <input type="number" class="form-control rounded-2" id="counter3_value"
                                                name="counter3_value" value="{{ setting('counter3_value',$lang) }}" step="any">
                                        <div class="nk-block-des text-danger">
                                            <p class="counter3_value_error error">{{ $errors->first('counter3_value') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-12 sandbox_mode_div mb-4">
                                    <input type="hidden" name="counter_enable" value="{{ setting('counter_enable') == 1 ? 1 : 0 }}">
                                    <label class="form-label"
                                           for="counter_enable">{{ __('enable') }}</label>
                                    <div class="setting-check">
                                        <input type="checkbox" value="1" id="counter_enable"
                                               class="sandbox_mode" {{ setting('counter_enable') == 1 ? 'checked' : '' }}>
                                        <label for="counter_enable"></label>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start align-items-center mt-30">
                                    <button type="submit" class="btn sg-btn-primary">{{ __('update') }}</button>
                                    @include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
