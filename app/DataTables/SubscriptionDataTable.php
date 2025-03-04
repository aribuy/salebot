<?php

namespace App\DataTables;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SubscriptionDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('plan_name', function ($subscription) {
                return view('backend.admin.subscription.column.plan_name', compact('subscription'));
            })->addColumn('date_time', function ($subscription) {
                return view('backend.admin.subscription.column.date', compact('subscription'));
            })->addColumn('next_billing', function ($subscription) {
                return view('backend.admin.subscription.column.next_billing', ['remainingPeriod' => $this->nextBilling($subscription), 'subscription' => $subscription]);
            })->addColumn('status', function ($subscription) {
                return view('backend.admin.subscription.column.status', compact('subscription'));
            })->addColumn('action', function ($subscription) {
                return view('backend.admin.subscription.column.action', compact('subscription'));
            })->addColumn('payment_method', function ($subscription) {
                return ucwords($subscription->payment_method);
            })->setRowId('id');
    }

    public function query(): QueryBuilder
    {
        $model = Subscription::with('plan', 'client.user');

        return $model->when($this->request->search['value'] ?? false, function ($query, $search) {
            $query->whereHas('client', function ($query) use ($search) {
                $query->where('client_id', 'like', "%$search%");
            })->orWhereHas('plan', function ($query) use ($search) {
                $query->where('name', 'like', "%$search%");
            })->orWhere('status', 'like', "%$search%");
        })->latest('id')->newQuery();
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
                    'lengthMenu'        => '_MENU_ '.__('subscription_per_page'),
                    'search'            => '',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('id')->data('DT_RowIndex')->title('#')->width(10),
            Column::computed('plan_name')->title(__('plan_name')),
            Column::computed('date_time')->title(__('date_time')),
            Column::computed('next_billing')->title(__('next_billing')),
            Column::computed('payment_method')->title(__('payment_method')),
            Column::computed('status')->title(__('status')),
            Column::computed('action')->title(__('action'))
                ->exportable(false)
                ->printable(false)
                ->searchable(false)->addClass('action-card')->width(10),
        ];
    }

    protected function filename(): string
    {
        return 'brand_'.date('YmdHis');
    }

    public function nextBilling($subscription): string
    {
        $created_at      = Carbon::parse($subscription->created_at);
        $current_date    = Carbon::now();

        if (@$subscription->plan->billing_period == 'monthly') {
            $expiry_date = $created_at->copy()->addMonthsNoOverflow(1);
        } else {
            $expiry_date = $created_at->copy()->addYear();
        }

        if ($current_date->gte($expiry_date)) {
            return 'Expired';
        }

        $remaining_days  = $current_date->diffInDays($expiry_date);
        $remaining_weeks = floor($remaining_days / 7);
        $remaining_days -= $remaining_weeks * 7;

        $remainingPeriod = '';

        if ($remaining_weeks >= 4) {
            $remaining_months = floor($remaining_weeks / 4);
            $remaining_weeks -= $remaining_months * 4;
            $remainingPeriod .= $remaining_months.' month'.($remaining_months > 1 ? 's ' : ' ');
        }

        if ($remaining_weeks > 0) {
            $remainingPeriod .= $remaining_weeks.' week'.($remaining_weeks > 1 ? 's ' : ' ');
        }

        if ($remaining_days > 0) {
            $remainingPeriod .= $remaining_days.' day'.($remaining_days > 1 ? 's' : '');
        }

        return $remainingPeriod;
    }
}
