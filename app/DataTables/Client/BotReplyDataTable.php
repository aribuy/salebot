<?php

namespace App\DataTables\Client;

use App\Models\BotReply;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BotReplyDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('name', function ($reply) {
                return view('backend.client.bot_reply.column.name', compact('reply'));
            })
            ->addColumn('type', function ($reply) {
                return view('backend.client.bot_reply.column.type', compact('reply'));
            })
            ->addColumn('status', function ($reply) {
                return view('backend.client.bot_reply.column.status', compact('reply'));
            })
            ->addColumn('keywords', function ($reply) {
                return $reply->keywords;
            })
            ->addColumn('action', function ($reply) {
                return view('backend.client.bot_reply.column.action', compact('reply'));
            })->setRowId('id');
    }

    public function query(BotReply $model)
    {

        $query = $model->withPermission();

        return $query
            ->when($this->request->search['value'] ?? false, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('reply_type', 'like', "%$search%");
                });
            })
            ->latest('id')->newQuery();
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
                    'lengthMenu'        => '_MENU_ '.__('bot_reply_list_per_page'),
                    'search'            => '',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('id')->data('DT_RowIndex')->title('#')->width(10),
            Column::computed('name')->title(__('name')),
            Column::computed('type')->title(__('type')),
            Column::computed('keywords')->title(__('keywords')),
			Column::computed('status')->title(__('status')),
            Column::computed('action')->title(__('action'))
                ->exportable(false)
                ->printable(false)
                ->searchable(false)->addClass('action-card')->width(10),
        ];
    }

    protected function filename(): string
    {
        return 'bot_reply_'.date('YmdHis');
    }
}
