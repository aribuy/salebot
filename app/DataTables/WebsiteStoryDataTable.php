<?php

namespace App\DataTables;

use App\Models\WebsiteStory;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class WebsiteStoryDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))

            ->addIndexColumn()
            ->addColumn('action', function ($story) {
                return view('backend.admin.website.story.column.action', compact('story'));
            })->addColumn('status', function ($story) {
                return view('backend.admin.website.story.column.status', compact('story'));
            })->addColumn('image', function ($story) {
                return view('backend.admin.website.story.column.image', compact('story'));
            })->addColumn('description', function ($story) {
                return @$story->language->description;
            })->setRowId('id');
    }

    public function query(): QueryBuilder
    {
        $model = WebsiteStory::with('language');

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
                    'lengthMenu'        => '_MENU_ '.__('story_per_page'),
                    'search'            => '',
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('id')->data('DT_RowIndex')->title('#')->searchable(false)->width(10),
            Column::make('description')->title(__('description')),
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
        return 'story'.date('YmdHis');
    }
}
