<?php

namespace App\DataTables\Client;

use App\Enums\TypeEnum;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ContactsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('created_at', function ($query) {
                return $query->created_at->format('d-m-Y');
            })
            ->addColumn('phone', function ($query) {
                return view('backend.client.whatsapp.contacts.partials.phone', compact('query'));
            })
            ->addColumn('status', function ($query) {
                return view('backend.client.whatsapp.contacts.partials.status', compact('query'));
            })

            ->addColumn('checkbox', function ($query) {
                return view('backend.client.whatsapp.contacts.partials.checkbox', compact('query'));
            })
            ->addColumn('contacts_list', function ($query) {
                $contactLists = '';
                if (! is_null($query->contactList)) {
                    foreach ($query->contactList as $contactList) {
                        @$contactLists .= $contactList->list->name.', ';
                    }
                    $contactLists = rtrim($contactLists, ', ');
                }
                return $contactLists;
            })

            ->addColumn('segments', function ($query) {
                $segments = '';
                if (! is_null($query->segmentList)) {
                    foreach ($query->segmentList as $segmentList) {
                        @$segments .= $segmentList->segment->title.', ';
                    }
                    $segments = rtrim($segments, ', ');
                }

                return $segments;
            })

            ->rawColumns(['created_at'])
            ->addColumn('action', function ($query) {
                return view('backend.client.whatsapp.contacts.partials.action', compact('query'));
            })
            ->addColumn('name', function ($query) {
                return view('backend.client.whatsapp.contacts.partials.name', compact('query'));
            })->setRowId('id');
    }

    public function query(Contact $model)
    {
        $query = $model
        ->with('contactList', 'segmentList')
            ->where('type', TypeEnum::WHATSAPP)
            ->withPermission()
            ->latest();
        $query->when($this->request->name, function ($query, $name) {
            $query->where('name', 'like', "%$name%");
        })
            ->when($this->request->phone, function ($query, $phone) {
                $query->where('phone', $phone);
            })
            ->when($this->request->country_id, function ($query, $country_id) {
                $query->where('country_id', $country_id);
            })
            ->when($this->request->status !== null, function ($query) {
                if ($this->request->status == '') {
                } elseif ($this->request->status == '1') {
                    $query->where('status', true);
                } elseif ($this->request->status == '0') {
                    $query->where('status', false);
                }
            })
            ->when($this->request->is_blacklist !== null, function ($query) {
                if ($this->request->is_blacklist == '') {
                } elseif ($this->request->is_blacklist == '1') {
                    $query->where('is_blacklist', true);
                } elseif ($this->request->is_blacklist == '0') {
                    $query->where('is_blacklist', false);
                }
            })

            ->when($this->request->segments_id, function ($query) {
                $query->whereHas('segmentList', function ($segmentQuery) {
                    $segmentQuery->where('segment_id', $this->request->segments_id);
                });
            })
            ->when($this->request->contact_list_id, function ($query) {
                $query->whereHas('contactList', function ($listQuery) {
                    $listQuery->where('contact_list_id', $this->request->contact_list_id);
                });
            })
            ->when(request('search')['value'] ?? false, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('username', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            });

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
                    'lengthMenu'        => '_MENU_ '.__('contacts_per_page'),
                    'search'            => '',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('checkbox')->title('<div class="custom-control custom-checkbox"><label class="custom-control-label" for="checkAll"><input id="checkAll" class="custom-control-input custom-checkbox" value="checkAll" type="checkbox"><span></span></label></div>')->width(10),
            Column::computed('id')->data('DT_RowIndex')->title('#')->width(10),
            Column::computed('name')->title(__('name')),
            Column::computed('phone')->title(__('phone')),
            Column::computed('contacts_list')->title(__('contacts_list')),
            Column::computed('segments')->title(__('segments')),
            Column::computed('status')->title(__('status')),
            Column::computed('action')->title(__('action'))
                ->exportable(false)
                ->printable(false)
                ->searchable(false)->addClass('action-card')->width(10),
        ];
    }

    protected function filename(): string
    {
        return 'contacts_'.date('YmdHis');
    }
}
