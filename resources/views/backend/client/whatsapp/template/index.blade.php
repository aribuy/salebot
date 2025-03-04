@extends('backend.layouts.master')
@section('title', __('templates'))
@section('content')
<section class="oftions">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col col-lg-12 col-md-12">
                <div class=" d-flex justify-content-between align-items-center flex-wrap">
                    <h3 class="section-title">{{__('template_management')}}</h3>
                    <div class="oftions-content-right mb-12 gap-2">
                        <div>
                            <a href="{{ route('client.templates.load-templete') }}" class="d-flex align-items-center btn sg-btn-primary gap-2">
                                <i class="las la-sync"></i>
                                <span>{{__('lode_template')}}</span>
                            </a>
                        </div>
                        <div>
                            <a target="__blank" href="https://business.facebook.com/wa/manage/message-templates/" class="d-flex align-items-center btn sg-btn-primary gap-2">
                                <i class="las la-facebook-f"></i>
                                <span>{{__('create_template_from_facebook_panel')}}</span>
                            </a>
                        </div>
                        @can('manage_template')
                        <div>
                            <a href="{{ route('client.template.create') }}" class="d-flex align-items-center btn sg-btn-primary gap-2">
                                <i class="las la-plus"></i>
                                <span>{{__('add_new')}}</span>
                            </a>
                        </div>
                        @endif
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
@endsection
@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"></script>
{{ $dataTable->scripts() }}
<script src="{{ static_asset('admin/js/custom/template.js') }}?v=1.0.0"></script>   
@endpush
