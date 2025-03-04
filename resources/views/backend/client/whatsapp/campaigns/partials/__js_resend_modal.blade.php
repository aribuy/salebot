<div class="modal fade" id="__js_resend_modal" tabindex="-1" aria-labelledby="__js_resend_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="__js_resend_modalLabel">{{ __('resend_campaign') }}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('client.whatsapp.campaign.resend')}}" id="resendForm">
                    @csrf
                    <input type="hidden" name="campaign_id" id="campaign_id" value="">
                    <p>{{ __('resend_this_campaign_to_subscribers_who_meet_the_following_criteria') }}</p>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="resend_option" id="resend_option2" checked
                            value="failed">
                        <label class="form-check-label" for="resend_option2">
                            {{ __('failed') }}
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="resend_option" id="resend_option3"
                            value="not_delivered">
                        <label class="form-check-label" for="resend_option3">
                            {{ __('sent_but_not_delivered') }}
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="resend_option" id="resend_option1"
                            value="did_not_read">
                        <label class="form-check-label" for="resend_option1">
                            {{ __('delivered_but_did_not_read') }}
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary sg-btn-primary"
                            data-bs-dismiss="modal">{{ __('close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
