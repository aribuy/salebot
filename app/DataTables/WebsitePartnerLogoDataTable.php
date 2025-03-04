<?php

namespace App\DataTables;

use App\Models\WebsitePartnerLogo;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class WebsitePartnerLogoDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', function ($partner_logo) {
                return view('backend.admin.website.partner_logo.column.action', compact('partner_logo'));
            })->addColumn('status', function ($partner_logo) {
                return view('backend.admin.website.partner_logo.column.status', compact('partner_logo'));
            })->addColumn('image', function ($partner_logo) {
                return view('backend.admin.website.partner_logo.column.image', compact('partner_logo'));
            })->addColumn('name', function ($partner_logo) {
                return @$partner_logo->language->name;
            })->setRowId('id');
    }

    public function query(): QueryBuilder
    {
        $model = WebsitePartnerLogo::with('language');
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
                    'lengthMenu'        => '_MENU_ '.__('partner_logo_per_page'),
                    'search'            => '',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('id')->data('DT_RowIndex')->title('#')->searchable(false)->width(10),
            Column::make('name')->title(__('name')),
            Column::computed('image')->title(__('image')),
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
