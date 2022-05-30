<?php

namespace App\DataTables;

use App\Models\ScraperJob;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ScraperJobsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('created_at', function ($query) {
                return $query->created_at->format('d-m-Y H:i:s');
            })
            ->addColumn('status', function ($query) {
                if($query->status == 0) {
                    return '<p class="text-warning">processing</p>';
                } else if ($query->status == 1) {
                    return '<p class="text-success">successful</p>';
                } else {
                    return '<p class="text-danger">failed</p>';
                }
            })
            ->addColumn('Scraper Criteria', function ($query) {
                return $query->scraperCriteria->keyword. ' in ' . $query->scraperCriteria->location;
            })
            ->addColumn('action', function ($query) {
                return view('scraper-jobs.datatable.action', ['scraperJob' => $query])->render();
            })
            ->rawColumns(['status', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ScraperJob $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ScraperJob $model)
    {
        $getJobs = $this->getJobs;
        if($getJobs == null) {
            return $model->newQuery();
        } else {
            return $model->newQuery()->where('scraper_criteria_id', $getJobs);
        }
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('scraperjobs-table')
                    ->columns($this->getColumns())
                    ->parameters([
                        'order' => [[0, 'desc']]
                    ])
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->buttons(
                        Button::make('create'),
                        Button::make('export'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id',
            'url',
            'platform',
            'status',
            'message',
            'last_index',
            'Scraper Criteria',
            'created_at',
            'end_at',
            'action' => [
                'searchable' => false,
                'orderable' => false
            ]
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'ScraperJobs_' . date('YmdHis');
    }
}
