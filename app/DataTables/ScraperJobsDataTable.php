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
            ->addColumn('Scraper Criteria', function ($query) {
                return $query->scraperCriteria->keyword. ' in ' . $query->scraperCriteria->location;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ScraperJob $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ScraperJob $model)
    {
        return $model->newQuery();
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
                        'order' => [[7, 'desc']]
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
            'url',
            'platform',
            'status',
            'failed',
            'exception',
            'last_index',
            'Scraper Criteria',
            'end_at',
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
