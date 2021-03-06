<?php

namespace App\DataTables;

use App\Models\GoogleBusiness;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class GoogleBusinessesDataTable extends DataTable
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
            ->addColumn('action', function ($query) {
                return view('google-businesses.datatable.action', ['googleBusiness' => $query])->render();
            })
            ->addColumn('Scraper_Criteria', function ($query) {
                return $query->scraperJob->scraperCriteria->keyword . ' in ' . $query->scraperJob->scraperCriteria->location;
            })
            ->addColumn('created_at', function ($query) {
                return $query->created_at->format('d-m-Y H:i:s');
            })
            ->addColumn('company', function ($query) {
                $target = '#myModal-sm-'. $query->id;
                return '<a class="text-dark" href="javascript:void(0)" data-toggle="modal"
                    data-target="'. $target .'">' . $query->company . '</a>';
            })
            ->addColumn('checkbox', '<input type="checkbox" name="getGoogleBusinessId[]" value="{{$id}}">')
            ->setRowClass(function ($query) {
                return $query->validated == 1 ? 'alert-danger' : '';
            })
            ->rawColumns(['action', 'checkbox', 'company']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\GoogleBusiness $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(GoogleBusiness $model)
    {
        $getJobBusinesses = $this->getJobBusinesses;
        $getBusiness = $this->getBusiness;
        if (isset($getBusiness)) {
            return $model->newQuery()->whereHas('scraperJob', function ($query) use ($getBusiness) {
                $query->where('scraper_criteria_id', $getBusiness);
            });
        } elseif (isset($getJobBusinesses)) {
            return $model->newQuery()->where('scraper_job_id', $getJobBusinesses);
        } else {
            return $model->newQuery();
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
            ->setTableId('googlebusinesses-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(9, 'asc')
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
            'checkbox' => [
                'title' => '',
                'data' => 'checkbox',
                'name' => 'checkbox',
                'orderable' => false,
                'searchable' => false,
                'exportable' => false,
                'printable' => false,
                'width' => '10px',
                'className' => 'dt-body-center',
            ],
            'id',
            'company',
            'phone',
            'address',
            'website',
            'industry',
            'Scraper_Criteria',
            'created_at',
            'validated' => [
                'className' => 'd-none',
            ],
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
        return 'GoogleBusinesses_' . date('YmdHis');
    }
}
