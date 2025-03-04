<div class="dropdown">
    <button class="btn sg-btn-primary btn-sm dropdown-toggle"
        type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="las la-plus"></i>
        {{ __('add_a_button') }}
    </button>
    <ul class="dropdown-menu">
        <li>
            <span class="add-btn-label">
                {{ __('quick_reply_buttons') }}
            </span>
        </li>
        <li>
            <a class="dropdown-item btn-item add_call_to_action"
                data-max="10" data-action="quick_reply"
                href="javascript:void(0);">
                {{ __('add_quick_reply') }}
            </a>
        </li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li>
            <span class="add-btn-label">
                {{ __('call_to_action_buttons') }}
            </span>
        </li>
        <li>
            <a class="dropdown-item btn-item add_call_to_action"
                data-max="2" data-action="visit_website"
                href="javascript:void(0);">
                {{ __('visit_website') }}
                <span class="d-block add-btn-notice">2
                    {{ __('buttons_maximum') }}</span>
            </a>
        </li>
        <li>
            <a class="dropdown-item btn-item add_call_to_action"
                data-max="1" data-action="call_phone_number"
                href="javascript:void(0);">
                {{ __('call_phone_number') }}
                <span class="d-block add-btn-notice">1
                    {{ __('buttons_maximum') }}</span>
            </a>
        </li>
        <li>
            <a class="dropdown-item btn-item add_call_to_action"
                data-max="1" data-action="copy_offer_code"
                href="javascript:void(0);">
                {{ __('copy_offer_code') }}
                <small><span class="d-block">1
                        {{ __('button maximum') }}</span></small>
            </a>
        </li>
    </ul>
</div>