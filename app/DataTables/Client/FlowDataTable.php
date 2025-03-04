<?php

namespace App\DataTables\Client;

use App\Models\Flow;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FlowDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('name', function ($flow) {
                return view('backend.client.flow.partials.name', compact('flow'));
            })
            ->addColumn('contact_list', function ($query) {
                return @$query->contactList->name;
            })
            ->addColumn('status', function ($flow) {
                return view('backend.client.flow.partials.status', compact('flow'));
            })
            ->addColumn('created_at', function ($query) {
                return $query->created_at->format('d-m-Y');
            })
            ->addColumn('action', function ($flow) {
                return view('backend.client.flow.partials.action', compact('flow'));
            })->setRowId('id');
    }

    public function query(): QueryBuilder
    {
        return Flow::where('client_id', auth()->user()->client_id)->latest()->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->setTableAttribute('style', 'width:99.8%')
            ->footerCallback('function ( row, data, start, end, display ) {

                $(".dataTables_length select").addClass("form-select form-select-lg without_search mb-3");
                selectionFields();
            }')
            ->parameters([
                'dom'        => 'Blfrtip',
                'buttons'    => [
                    [],
                ],
                'lengthMenu' => [[10, 25, 50, 100, 250], [10, 25, 50, 100, 250]],
                'language'   => [
                    'searchPlaceholder' => __('search'),
                    'lengthMenu'        => '_MENU_ ' . __('flow_per_page'),
                    'search'            => '',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('id')->data('DT_RowIndex')->title('#')->searchable(false)->width(10),
            Column::computed('name')->title(__('name')),
            Column::computed('contact_list')->title(__('contact_list')),
            Column::computed('status')->title(__('status'))->searchable(false)->exportable(false)->printable(false),
            Column::computed('created_at')->title(__('created_at')),
            Column::computed('action')->addClass('action-card')->title(__('action'))->searchable(false)->exportable(false)->printable(false),
        ];
    }

    protected function filename(): string
    {
        return 'team_' . date('YmdHis');
    }
}
