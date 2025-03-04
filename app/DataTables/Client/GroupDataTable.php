<?php
namespace App\DataTables\Client;
use App\Models\BotGroup;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
class GroupDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('name', function ($query) {
                return view('backend.client.telegram.groups.name', compact('query'));
            })
            ->addColumn('total_subscriber', function ($query) {
                return $query->subscriber->count();
            })
            ->addColumn('total_blocked', function ($query) {
                return  $query->subscriber->where('is_blacklist', 1)->count();
            })
            ->addColumn('created_at', function ($query) {
                return $query->created_at->format('d-m-Y');
            })
            ->rawColumns(['created_at'])
            ->addColumn('status', function ($query) {
                return view('backend.client.telegram.groups.status', compact('query'));
            })
            ->addColumn('action', function ($query) {
                return view('backend.client.telegram.groups.action', compact('query'));
            })
           
            ->setRowId('id');
    }

    public function query(BotGroup $model)
    {
        $query = $model->latest('id')->withPermission()->newQuery();
        return $query;
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
                    'lengthMenu'        => '_MENU_ ' . __('group_per_page'),
                    'search'            => '',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('id')->data('DT_RowIndex')->title('#')->width(10),
            Column::computed('name')->title(__('title')),
            Column::computed('total_subscriber')->title(__('total_subscriber')),
            Column::computed('total_blocked')->addClass('text-start')->title(__('total_blocked')),
            Column::computed('status')->addClass('text-start')->title(__('status')),
            Column::computed('action')->title(__('action'))
                ->exportable(false)
                ->printable(false)
                ->searchable(false)->addClass('action-card')->width(10),
        ];
    }

    protected function filename(): string
    {
        return 'group_' . date('YmdHis');
    }
}
