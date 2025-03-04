@extends('backend.layouts.master')
@section('title', __('templates'))
@section('content')
<section class="oftions">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col col-lg-12 col-md-12">
                <div class="d-flex align-items-center justify-content-between mb-12">
                    <h3 class="section-title">{{__('template_management')}}</h3>
                    <div class="d-flex align-items-center gap-2">
                        <div>
                            <a href="{{ route('client.telegram.campaign.create') }}" class="d-flex align-items-center btn sg-btn-primary gap-2">
                                <i class="las la-plus"></i>
                                <span>{{__('add_new')}}</span>
                            </a>
                        </div>
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
@include('backend.common.delete-script')
@endsection
@push('js')
    {{ $dataTable->scripts() }}
@endpush
