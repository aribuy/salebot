<?php

namespace App\DataTables;

use App\Models\WebsiteFeature;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class WebsiteFeatureDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', function ($feature) {
                return view('backend.admin.website.feature.column.action', compact('feature'));
            })->addColumn('status', function ($feature) {
                return view('backend.admin.website.feature.column.status', compact('feature'));
            })->addColumn('type', function ($feature) {
                return view('backend.admin.website.feature.column.type', compact('feature'));
            })->addColumn('image', function ($feature) {
                return view('backend.admin.website.feature.column.image', compact('feature'));
            })->addColumn('title', function ($feature) {
                return @$feature->language->title;
            })->setRowId('id');
    }

    public function query(): QueryBuilder
    {
        $model = WebsiteFeature::with('language');
        return $model->latest()->newQuery();
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
                    'lengthMenu'        => '_MENU_ '.__('feature_per_page'),
                    'search'            => '',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('id')->data('DT_RowIndex')->title('#')->searchable(false)->width(10),
            Column::make('title')->title(__('title')),
            Column::computed('image')->title(__('image')),
            Column::computed('type')->title(__('type')),
            Column::computed('status')->title(__('status'))->exportable(false)
                ->printable(false)->width(10),
            Column::computed('action')->title(__('action'))
                ->exportable(false)
                ->printable(false)
                ->searchable(false)->addClass('action-card')->width(10),

        ];
    }

    protected function filename(): string
    {
        return 'client_'.date('YmdHis');
    }
}
