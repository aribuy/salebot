<div class="row">
    <div class="col-lg-6">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h5 class="accordion-header">
                    <a class="accordion-button py-2" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                        aria-expanded="true" aria-controls="collapseOne">
                        {{ __('why_whatsapp_template_rejected') }}
                    </a>
                </h5>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="">
                            <h6>{{__('common_rejection_reasons')}}</h6>
                            <p class="text-muted">{{__('submissions_are_commonly_rejected')}}</p>
                            <ol>
                                <li class="font-14">1. {{__('common_rejection_line_1')}} <code>{ { 1 } }</code>.
                                </li>
                                <li class="font-14">2. {{__('common_rejection_line_2')}} #,
                                    $, or %.</li>
                                <li>3. {{__('common_rejection_line_3')}}
                                    <code>{ { 1 } }, { { 2 } }, { { 4 } }, { { 5 } }</code>
                                    {{__('are_defined_but')}} <code>{ { 3 } }</code> {{__('does_not_exist')}}.
                                <li class="font-14">4. {{__('common_rejection_line_4')}} <a
                                        target="__blank" class="lern-more" href="https://business.whatsapp.com/policy">
                                        {{__('whatsApp’s_commerce_policy:')}}</a> {{__('whatsApp’s_commerce_policy_line')}}</li>
                                <li class="font-14">5. {{__('common_rejection_line_5')}} <a
                                        target="__blank" class="lern-more"
                                        href="https://business.whatsapp.com/policy">{{__('whatsApps_business_policy:')}}</a> {{__('whatsApps_business_policy_line')}}</li>
                                <li class="font-14">6. {{__('common_rejection_line_6')}}</li>
                                <li class="font-14">7. {{__('common_rejection_line_7')}}</li>
                            </ol>
                            <p class="text-muted font-14">{{__('rejection_notification')}} <a target="__blank" class="lern-more"
                                    href="https://business.facebook.com/business-support-home">
                                    {{__('business_support_home')}}</a>. {{__('You_can_view_rejections')}} <strong>{{__('account_overview')}}</strong> &gt; <strong>{{__('view_my_accounts')}}</strong> &gt; <em>{{__('your_meta')}}</em>
                                &gt; <em>{{__('WABA')}}</em> &gt; <em>{{__('rejected_message_templates')}}</em>.
                                {{__('rejection_info')}}</p>
                            <p class="text-muted font-14">{{__('rejection_info_line')}}</p>
                            <p class="text-muted font-14">{{__('this_check_does_not')}} <strong>{{__('AUTHENTICATION')}}</strong>.</p>
                            <p><a target="__blank" class="lern-more"
                                    href="https:https://developers.facebook.com/docs/whatsapp/message-templates/guidelines#common-rejection-reasons">
                                    {{ __('read_more') }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
