@extends('backend.layouts.master')
@section('title', __('subscription'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header-top d-flex justify-content-between align-items-center">
                        <h3 class="section-title">{{ __('subscription') }}</h3>
                        <div class="oftions-content-right mb-12">
                            <a href="" data-bs-toggle="modal" data-bs-target="#subscription"
                               class="d-flex align-items-center btn sg-btn-primary gap-2">
                                <i class="las la-plus"></i>
                                <span>{{ __('add_subscription') }}</span>
                            </a>
                        </div>
                    </div>
                    <div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="default-list-table table-responsive yajra-dataTable">
                                    {{ $dataTable->table() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('backend.admin.subscription.model.add_credit')
    @include('backend.admin.subscription.model.create_subscription')
    @include('backend.common.delete-script')
@endsection
@push('js')
    {{ $dataTable->scripts() }}
    <script>
        $(document).ready(function() {
            $('#credit').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var subscriptionId = button.data('subscription-id');
                var modal = $(this);
                modal.find('#subscription_id').val(subscriptionId);
            });
        });
    </script>
@endpush