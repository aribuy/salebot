@extends('backend.layouts.master')
@section('title', __('telegram_settings'))
@section('content')
    @push('css_asset')
        <style>
            .disabled {
                opacity: 0.6;
                cursor: not-allowed;
                pointer-events: none;
            }
        </style>
    @endpush
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="section-title">{{ __('telegram_settings') }}</h3>
                <div class="default-tab-list default-tab-list-v2  bg-white redious-border p-20 p-sm-30">
                    <form action="{{ route('client.settings.telegram.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row row-eq-height mb-3">
                            <div class="col-lg-6">
                                <div class="card h-100 ">
                                    <div class="card-header d-flex justify-content-between">
                                        <h5>{{ __('telegram_settings') }}</h5>
                                        @if (setting('telegram_settings_video_url'))
                                            <a href="#" class="d-flex align-items-center gap-2" data-bs-toggle="modal"
                                                data-bs-target="#config_tutorial">
                                                <i class='lab la-youtube' style='font-size:40px'></i>
                                            </a>
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <label for="access_token" class="form-label">{{ __('bot_token') }}</label>
                                            <div class="input-group">
                                                <input type="text"
                                                    value="{{ isDemoMode() ? '******************' : @Auth::user()->client->telegramSetting->access_token }}"
                                                    name="access_token"
                                                    class="form-control @if (!empty(@Auth::user()->client->telegramSetting->access_token)) disabled @endif"
                                                    id="access_token_input" placeholder="{{ __('bot_token') }}"
                                                    aria-label="{{ __('enter_bot_token') }}" aria-describedby="bot_token"
                                                    required>
                                                <span class="input-group-text copy-text" id="access_token_copy">
                                                    <i class="la la-copy"></i>
                                                </span>
                                            </div>
                                            <div class="nk-block-des text-danger">
                                                <p class="first_name_error error">
                                                    {{ $errors->first('access_token') }}</p>
                                            </div>
                                            @if (@Auth::user()->client->telegramSetting)
                                                <div class="">
                                                    <div class="p-2 border rounded bg-light my-2">
                                                        @if (
                                                            !empty(@Auth::user()->client->telegramSetting->access_token) &&
                                                                !empty(@Auth::user()->client->telegramSetting->scopes))
                                                            <div class="mb-2">
                                                                <strong>{{ __('name') }}:</strong>
                                                                {{ Auth::user()->client->telegramSetting->name }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>{{ __('username') }}:</strong>
                                                                {{ Auth::user()->client->telegramSetting->username }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>{{ __('bot_id') }}:</strong>
                                                                {{ Auth::user()->client->telegramSetting->bot_id }}
                                                            </div>
                                                            <div class="mb-2">
                                                                <strong>{{ __('token_verified') }}:</strong>
                                                                @if (Auth::user()->client->telegramSetting->token_verified)
                                                                    <i class="las la-check-circle text-success"></i>
                                                                @else
                                                                    <i class="las la-times-circle text-danger"></i>
                                                                @endif
                                                            </div>
                                                            <?php
                                                            $scopes = @Auth::user()->client->telegramSetting->scopes ?? [];
                                                            $requiredScopes = config('static_array.telegram_required_scopes');
                                                            ?>
                                                            <ul class="list-unstyled list-inline">
                                                                @foreach ($requiredScopes as $scope)
                                                                    <li class="list-inline-item">
                                                                        @if (in_array($scope, $scopes))
                                                                            <i class="las la-check-circle text-success"></i>
                                                                        @else
                                                                            <i class="las la-times-circle text-danger"></i>
                                                                        @endif
                                                                        {{ $scope }}
                                                                    </li>
                                                                @endforeach
                                                                @foreach ($scopes as $scope)
                                                                    @if (!in_array($scope, $requiredScopes))
                                                                        <li class="list-inline-item">
                                                                            <i class="las la-check-circle text-success"></i>
                                                                            {{ $scope }}
                                                                        </li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="d-flex justify-content-end align-items-center mt-30 gap-2">
                                            @if (@Auth::user()->client->telegramSetting->access_token)
                                                <button type="button" id="remove_access_token"
                                                    data-id="{{ @Auth::user()->client->telegramSetting->id }}"
                                                    data-url="{{ route('client.settings.remove-token', @Auth::user()->client->telegramSetting->id) }}"
                                                    class="btn text-white btn-danger __js_delete">
                                                    <i class="las la-trash-alt"></i> {{ __('remove') }}
                                                </button>
                                            @endif
                                            @if (@Auth::user()->client->telegramSetting)
                                                <button type="button" class="btn btn-secondary" id="sync_telegram"
                                                    data-id="{{ @Auth::user()->client->telegramSetting->id }}"
                                                    data-url="{{ route('client.telegram.settings.sync', @Auth::user()->client->telegramSetting->id) }}">
                                                    <i class="las la-sync-alt"></i> {{ __('sync') }}
                                                </button>
                                            @endif
                                            <button type="submit" class="btn sg-btn-primary">{{ __('save') }}</button>
                                            @include('backend.common.loading-btn', [
                                                'class' => 'btn sg-btn-primary',
                                            ])
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Telegram Bot Creation Instructions -->
                            <div class="col-lg-6">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h5>{{ __('line') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <ol>
                                            <li>{{ __('1') }}. {{ __('line_one') }}</li>
                                            <li>{{ __('2') }}. {{ __('line_two') }}</li>
                                            <li>{{ __('3') }}. {{ __('line_three') }}</li>
                                            <li>{{ __('4') }}. {{ __('line_four') }}</li>
                                            <li>{{ __('5') }}. {{ __('line_five') }}</li>
                                        </ol>
                                        <ol>
                                            <li>{{ __('6') }}. {{ __('line_six') }}
                                            </li>
                                            <ol>
                                                <li>
                                                    <i class="las la-info-circle"></i>
                                                    {{ __('add_the_bot') }}
                                                </li>
                                                <ol>
                                                    <li>
                                                        <i class="las la-long-arrow-alt-right"></i>
                                                        {{ __('open_the_group') }}
                                                    </li>
                                                    <li>
                                                        <i class="las la-long-arrow-alt-right"></i>
                                                        {{ __('click_on_the_group_name') }}
                                                    </li>
                                                    <li>
                                                        <i class="las la-long-arrow-alt-right"></i>
                                                        {{ __('tap_on_the_three_dots_in') }}
                                                    </li>
                                                    <li>
                                                        <i class="las la-long-arrow-alt-right"></i>
                                                        {{ __('click_on.') }}
                                                    </li>
                                                    <li>
                                                        <i class="las la-long-arrow-alt-right"></i>
                                                        {{ __('search_for_your_bot') }}
                                                    </li>
                                                </ol>
                                            </ol>
                                            <li>{{ __('7') }}. {{ __('use_the_telegram') }}</li>
                                            <ol>
                                                <li>{{ __('now_you_can_use_your_bot_token') }}
                                                </li>
                                            </ol>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-lg" id="config_tutorial" tabindex="-1" aria-labelledby="config_tutorial"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="embed-responsive videowrapper">
                    <iframe class="embed-responsive-item"
                        src="https://www.youtube.com/embed/{{ getYoutubeVideoId(setting('telegram_settings_video_url')) }}"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
    @include('backend.common.delete-script')
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.copy-text').click(function() {
                var inputField = $(this).closest('.input-group').find('input');
                inputField.select();
                document.execCommand("copy");
                toastr.success("{{ __('copied') }}");
            });
        });

        $(document).on('click', '.__js_delete', function() {
            confirmationAlert(
                $(this).data('url'),
                $(this).data('id'),
                'Yes, Delete It!'
            )
        })

        const confirmationAlert = (url, data_id, button_test = 'Yes, Confirmed it!') => {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: button_test,
            }).then((confirmed) => {
                if (confirmed.isConfirmed) {
                    axios.post(url, {
                            data_id: data_id
                        })
                        .then(response => {
                            console.log(response);
                            Swal.fire(
                                response.data.message,
                                '',
                                response.data.status == true ? 'success' : 'error',
                            );
                            location.reload();
                            // Swal.fire(...response.data.message);
                        })
                        .catch(error => {
                            console.log(error);
                            Swal.fire(...error.response.data);
                        })
                }
            });
        };
        $('#sync_telegram').click(function() {
            var button = $(this);
            var url = button.data('url');
            button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' +
                '{{ __('syncing') }}');
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    id: button.data('id')
                },
                success: function(data) {
                    button.html('<i class="las la-sync-alt"></i> {{ __('sync') }}');
                    if (data.status) {
                        toastr.success(data.message);
                        location.reload();
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function(xhr, status, error) {
                    button.html('<i class="las la-sync-alt"></i> {{ __('sync') }}');
                    console.error('Error:', error);
                }
            });
        });
    </script>
@endpush
