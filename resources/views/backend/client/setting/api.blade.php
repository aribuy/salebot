@extends('backend.layouts.master')
@section('title', __('api'))
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="section-title">{{ __('api_details') }}</h3>
                <form>
                        <div class="row row-eq-height mb-3">
                            <div class="col-xl-6">
                                <div class="card h-100 redious-border">
                                    <div class="card-header">
                                        <h5>{{ __('api_credentials') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <label for="webhook_callback_url"
                                                class="form-label">{{ __('api_endpoint') }}</label>
                                            <div class="input-group">
                                                <input type="url" value="{{ url('/') }}" readonly
                                                    name="webhook_callback_url" class="form-control"
                                                    placeholder="{{ __('enter_webhook_callback_url') }}"
                                                    aria-label="{{ __('enter_webhook_callback_url') }}"
                                                    aria-describedby="webhook_callback_url">
                                                <span class="input-group-text copy-text" id="webhook_callback_url"><i
                                                        class="la la-copy"></i></span>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label for="webhook_verify_token"
                                                class="form-label">{{ __('api_key') }}</label>
                                            <div class="input-group">
                                                <input type="url"
                                                    value="{{ isDemoMode() ? '******************' : @Auth::user()->client->api_key }}"
                                                    readonly name="webhook_verify_token" class="form-control"
                                                    placeholder="{{ __('enter_webhook_verify_token') }}"
                                                    aria-label="{{ __('enter_webhook_verify_token') }}"
                                                    aria-describedby="webhook_verify_token">
                                                <span class="input-group-text copy-text" id="webhook_verify_token"><i
                                                        class="la la-copy"></i></span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card h-100 redious-border">
                                    <div class="card-header">
                                        <h5>{{__('api_documentation')}}</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled">
                                            <li>{{__('api_documentation_description')}}</li>
                                        </ul>
                                        @if(setting('api_documentation_url') !='')
                                            <div class="text-center">
                                                <a href="{{setting('api_documentation_url')}}" target="_blank" class="btn btn-sm btn-primary gap-2  mt-20 mb-20">
                                                    <span>{{__('see_api_documentation_here')}}</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $('.copy-text').click(function() {
                var inputField = $(this).closest('.input-group').find('input');
                inputField.select();
                document.execCommand("copy");
                toastr.success("{{__('copied')}}");
            });
        });
    </script>
@endpush
