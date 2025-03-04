@extends('backend.layouts.master')
@section('title', __('contacts_list'))
@section('content')
	<section class="oftions">
		<div class="container-fluid">
			<div class="row justify-content-center">
				<div class="col col-lg-12 col-md-12">
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
<script>

	const refreshDataTable = () => {
        $('#dataTableBuilder').DataTable().ajax.reload();
    }
</script>

@endpush

