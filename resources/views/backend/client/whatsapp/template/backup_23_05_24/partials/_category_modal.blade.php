<!-- Category Modal -->
<div class="modal fade" id="categoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="categoryModalLabel">{{ __('learn_more_about_categories') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class=" m-0 p-0 border-0 border-bottom mb-3">
                    {{ __('learn_more_about_categories_header') }}
                    <p>
                        <a class="lern-more" target="_blank"
                            href="https://developers.facebook.com/docs/whatsapp/updates-to-pricing/new-template-guidelines/"
                            tabindex="0">{{ __('see_more_examples_of_template_categories') }}</a>
                        </p>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="card m-0 p-0 border-0">
                            <div class="card-body m-0 p-0 border-0">
                                <h6 class="mb-2 card-title">{{ __('marketing') }}</h6>
                                <img src="https://static.xx.fbcdn.net/rsrc.php/v1/yx/r/lbSTJEOXQcG.jpg" alt="{{ __('marketing') }}" class="img-fluid card-img-top">
                                <p class="card-text">{{ __('marketing_category_text') }}</p>
                                <p class="card-text"><small>{{ __('marketing_category_example') }}</small></p>
                            </div>
                        </div>

                    </div>
                    <div class="col-4">
                        <div class="card m-0 p-0 border-0">
                            <div class="card-body m-0 p-0 border-0">
                                <h6 class="mb-2 card-title">{{ __('utility') }}</h6>
                                <img src="https://static.xx.fbcdn.net/rsrc.php/v1/yq/r/moqw5KmVTtB.jpg" alt="{{ __('utility') }}" class="img-fluid card-img-top">
                                <p class="card-text">{{ __('utility_category_text') }}</p>
                                <p class="card-text"><small>{{ __('utility_category_example') }}</small></p>
                            </div>
                        </div>

                    </div>
                    <div class="col-4">
                        <div class="card m-0 p-0 border-0">
                            <div class="card-body m-0 p-0 border-0">
                                <h6 class="mb-2 card-title">{{ __('authentication') }}</h6>
                                <img src="https://static.xx.fbcdn.net/rsrc.php/v1/yv/r/bpIMESkoiQ5.jpg" alt="{{ __('authentication') }}" class="img-fluid card-img-top">
                                <p class="card-text">{{ __('authentication_category_text') }}</p>
                                <p class="card-text"><small>{{ __('authentication_category_example') }}</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary sg-btn-primary" data-bs-dismiss="modal">{{ __('close') }}</button>
            </div>
        </div>
    </div>
</div>
