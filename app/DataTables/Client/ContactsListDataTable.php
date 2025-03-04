<?php

namespace App\DataTables\Client;

use App\Models\ContactsList;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ContactsListDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('created_at', function ($query) {
                return $query->created_at->format('d-m-Y');
            })
            ->rawColumns(['created_at'])
            ->addColumn('action', function ($query) {
                return view('backend.client.whatsapp.contacts_list.action', compact('query'));
            })
            ->addColumn('title', function ($query) {
                return view('backend.client.whatsapp.contacts_list.title', compact('query'));
            })
            ->addColumn('contacts', function ($query) {
                return view('backend.client.whatsapp.contacts_list.contacts', compact('query'));
            })
            ->addColumn('read_by', function ($query) {
                return view('backend.client.whatsapp.contacts_list.read_by', compact('query'));
            })->setRowId('id');
    }

    public function query(ContactsList $model)
    {

        $query = $model->latest('id')
            ->when(request('search')['value'] ?? false, function ($query, $search) {
                $query->where('name', 'like', "%$search%");
            })->withPermission()->newQuery();

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
                    'lengthMenu'        => '_MENU_ '.__('contacts_list_per_page'),
                    'search'            => '',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('id')->data('DT_RowIndex')->title('#')->width(10),
            Column::computed('name')->title(__('title')),
            Column::computed('contacts')->title(__('contacts')),
            Column::computed('read_by')->title(__('read_by')),
            Column::computed('action')->title(__('action'))
                ->exportable(false)
                ->printable(false)
                ->searchable(false)->addClass('action-card')->width(10),
        ];
    }

    protected function filename(): string
    {
        return 'contact_list_'.date('YmdHis');
    }
}
