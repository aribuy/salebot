@extends('backend.layouts.master')
@section('title', __('bot_&_quick_replies_management'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header-top d-flex justify-content-between align-items-center">
                        <h3 class="section-title">{{__('bot_&_quick_replies_management') }}</h3>
                        <div class="oftions-content-right mb-12">
                            <a href="{{ route('client.bot-reply.create') }}" class="d-flex align-items-center btn sg-btn-primary gap-2">
                                <i class="las la-plus"></i>
                                <span>{{__('add_new') }}</span>
                            </a>
                        </div>
                    </div>
                    <div class="bg-white rounded-20 p-20 p-sm-30">
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
    </section> <!-- End Oftions Section -->
    @include('backend.common.delete-script')

@endsection
@push('js')
    {{ $dataTable->scripts() }}
@endpush
