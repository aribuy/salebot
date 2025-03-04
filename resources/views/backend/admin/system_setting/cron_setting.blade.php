@extends('backend.layouts.master')
@section('title', __('cron_job_setting'))
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col-lg-6 col-md-8">
                <div class="card h-100 ">
                    <div class="card-header">
                        <h5>{{ __('cron_job_setting') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="mb-4">
                                <p>{{ __('cron_setup_instruction_line1') }}</p>
                                <p>{{ __('cron_setup_instruction_line2') }}</p>
                            </div>
                        </div>
                        <label for="cron_command" class="form-label">{{ __('cron_command') }}</label>
                        <div class="input-group">
                            <input type="url" value="wget -q -O- {{ url('cron') . '/' . setting('cron_key') }}" readonly
                                name="webhook_callback_url" class="form-control" placeholder="{{ __('cron_command') }}"
                                aria-label="{{ __('cron_command') }}" aria-describedby="cron_command">
                            <span class="input-group-text copy-text" id="cron_command"><i class="la la-copy"></i></span>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('cron.run.manually') }}" class="btn btn-sm btn-primary gap-2  mt-20 mb-20">
                                <span>{{ __('run_cron_manually') }}</span>
                            </a>
                        </div>
                        <div class="form-group">
                            <label for="campaign-message-limit">{{ __('campaign_message_limit_per_execution') }}</label>
                            <div class="input-group">
                                <input type="number" name="message_limit" value="{{ setting('message_limit') ?? 100 }}"
                                    min="1" max="100000" class="form-control" id="campaign-message-limit"
                                    placeholder="{{ __('enter_campaign_message_limit_per_execution') }}"
                                    aria-label="Campaign Message Limit" aria-describedby="campaign-message-limit-help">
                                <button class="btn btn-primary" type="button"
                                    data-url="{{ route('admin.message-limit.update') }}"
                                    id="update-campaign-message-limit" style="height: 40px;line-height: 19px">{{ __('Update') }}</button>
                            </div>
                            <small id="campaign-message-limit-help"
                                class="form-text text-muted">{{ __('enter_the_maximum_number_of_campaign_messages_allowed_per_execution') }}</small>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
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
        $(document).ready(function() {
            $('#update-campaign-message-limit').click(function() {
                var messageLimit = $('#campaign-message-limit').val();
                var url = $(this).data('url');
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: url,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    contentType: 'application/json',
                    data: JSON.stringify({
                        message_limit: messageLimit
                    }),
                    success: function(response) {
                        toastr.success(response.success);
                        console.log('Message limit updated successfully');
                    },
                    error: function(xhr, status, error) {
                        toastr.error(xhr.responseJSON.error);
                        console.error('Failed to update message limit:', error);
                    }
                });
            });
        });
    </script>
@endpush
