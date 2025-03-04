<?php

namespace App\DataTables\Client;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class WhatsAppCampaignDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        if ($this->request->has('campaign_type')) {
            $query->where('campaign_type', $this->request->campaign_type);
        }

        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('name', function ($query) {
                return view('backend.client.whatsapp.campaigns.partials.name', compact('query'));
            })
            ->addColumn('total_contact', function ($query) {
                return $query->total_contact;
            })
            ->addColumn('statistics', function ($query) {
                return view('backend.client.whatsapp.campaigns.partials.statistics', compact('query'));
            })
            ->addColumn('status', function ($query) {
                return view('backend.client.whatsapp.campaigns.partials.status', compact('query'));
            })

            ->addColumn('created_at', function ($query) {
                return dateTimeClientTimeZoneWise($query->created_at);
            })
            ->addColumn('action', function ($query) {
                return view('backend.client.whatsapp.campaigns.partials.action', compact('query'));
            })
            ->setRowId('id');
    }

    public function query(Campaign $model)
    {
        $query = $model->withPermission()->latest();
        if ($this->request->has('campaign_type')) {
            $query->where('campaign_type', $this->request->campaign_type);
        }
        $query->when($this->request->name, function ($query, $name) {
            $query->where('campaign_name', 'like', "%$name%");
        })
            ->when($this->request->phone, function ($query, $phone) {
                $query->where('phone', $phone);
            })
            ->when($this->request->country_id, function ($query, $country_id) {
                $query->where('country_id', $country_id);
            })
            ->when($this->request->template_id, function ($query, $template_id) {
                $query->where('template_id', $template_id);
            })
            ->when($this->request->contact_list_id, function ($query, $contact_list_id) {
                if ($contact_list_id) {
                    $query->whereRaw('JSON_CONTAINS(contact_list_id, ?)', [json_encode($contact_list_id)]);
                }
            })
            ->when($this->request->segment_id, function ($query, $segment_id) {
                if ($segment_id) {
                    $query->whereRaw('JSON_CONTAINS(segment_id, ?)', [json_encode($segment_id)]);
                }
            })
            // ->when($this->request->contact_list_id, function ($query, $contact_list_id) {
            //     $query->where('contact_list_id', $contact_list_id);
            // })
            // ->when($this->request->segment_id, function ($query, $segment_id) {
            //     $query->where('segment_id', $segment_id);
            // })
            ->when($this->request->created_at ?? false, function ($query, $created_at) {
                $dateRange = $this->parseDate($created_at);
                $query->whereBetween('created_at', $dateRange);
            })
            ->when(request('search')['value'] ?? false, function ($query, $search) {
                $query->where('campaign_name', 'like', "%$search%");
            });

        return $query;
    }

    private function parseDate($date_range)
    {
        $dates      = explode('to', $date_range);

        if (count($dates) == 1) {
            $dates[1] = $dates[0];
        }

        $start_date = trim($dates[0]);
        $end_date   = trim($dates[1]);

        $start_date = $start_date.' 00:00:00';
        $end_date   = $end_date.' 23:59:59';

        return [
            Carbon::parse($start_date)->format('Y-m-d H:s:i'),
            Carbon::parse($end_date)->format('Y-m-d H:s:i'),
        ];
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
                    'lengthMenu'        => '_MENU_ '.__('campaigns_per_page'),
                    'search'            => '',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('id')->data('DT_RowIndex')->title('#')->width(10),
            Column::computed('name')->title(__('name')),
            Column::computed('total_contact')->title(__('total_contact')),
            Column::computed('created_at')->title(__('created_at')),
            Column::computed('statistics')->title(__('statistics')),
            Column::computed('status')->title(__('status')),
            Column::computed('action')->title(__('action'))
                ->exportable(false)
                ->printable(false)
                ->searchable(false)->addClass('action-card')->width(10),
        ];
    }

    protected function filename(): string
    {
        return 'campaign_'.date('YmdHis');
    }
}
