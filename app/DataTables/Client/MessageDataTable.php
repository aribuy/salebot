<?php

namespace App\DataTables\Client;

use App\Models\Message;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MessageDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('name', function (Message $message) {
                return @$message->contact->name;
            })
            ->addColumn('phone', function (Message $message) {
                return isDemoMode() ? '+***********' : @$message->contact->phone;
            })
            ->addColumn('schedule_at', function (Message $message) {
                return dateTimeClientTimeZoneWise($message->schedule_at);
            })
            ->addColumn('status', function ($message) {
                return view('backend.client.whatsapp.campaigns.status', compact('message'));
            })
            ->setRowId('id');
    }

    public function query()
    {
        $query = Message::query()->where('campaign_id', $this->id);

        return $query->when($this->request->status, function ($query, $status) {
            if ($status === 'delivered') {
                return $query->whereIn('status', ['read', 'delivered']);
            } else {
                return $query->where('status', $status);
            }
        })
            ->withPermission()
            ->latest('id');
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
                    'lengthMenu'        => '_MENU_ '.__('segments_per_page'),
                    'search'            => '',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('id')->data('DT_RowIndex')->title('#')->width(10),
            Column::computed('name')->title(__('name')),
            Column::computed('phone')->title(__('phone'))
                ->printable(false)->width(10),
            Column::computed('schedule_at')->title(__('scheduled_at')),
            Column::computed('status')->title(__('status'))->addClass('text-end'),
        ];
    }

    protected function filename(): string
    {
        return 'message_'.date('YmdHis');
    }
}
