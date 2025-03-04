<?php

namespace App\DataTables;

use App\Models\Client;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ClientDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()->addColumn('phone', function ($client) {
                return isDemoMode() ? '+***********' : countryCode(@$client->user->phone_country_id).@$client->user->phone;
            })
            ->addIndexColumn()->addColumn('name', function ($client) {
                return $client->company_name;
            })
            ->addIndexColumn()->addColumn('email', function ($client) {
                return isDemoMode() ? '****@****.***' : @$client->user->email;
            })
            ->addColumn('plan', function ($client) {
                return view('backend.admin.client.plan', compact('client'));
            })
            ->addColumn('status', function ($client) {
                return view('backend.admin.client.status', compact('client'));
            })->addColumn('action', function ($client) {
                return view('backend.admin.client.action', compact('client'));
            })->addColumn('logo', function ($client) {
                return view('backend.admin.client.image', compact('client'));
            })->setRowId('id');
    }

    public function query(): QueryBuilder
    {

        $model = Client::with('user');

        return $model
            ->when($this->request->search['value'] ?? false, function ($query, $search) {
                $query->where('company_name', 'like', "%$search%")
                    ->orWhere('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ;
            })
            ->latest()
            ->newQuery();
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
                    'lengthMenu'        => '_MENU_ '.__('client_per_page'),
                    'search'            => '',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('id')->data('DT_RowIndex')->title('#')->searchable(false)->width(10),
            Column::computed('logo')->title(__('logo')),
            Column::computed('name')->title(__('name')),
            Column::make('email')->title(__('email')),
            Column::computed('phone')->title(__('phone')),
            Column::make('plan')->title(__('plan')),
            Column::computed('status')->title(__('status'))->searchable(false)->exportable(false)->printable(false),
            Column::computed('action')->addClass('action-card')->title(__('action'))->searchable(false)->exportable(false)->printable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Currency_'.date('YmdHis');
    }
}
