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
            ->addColumn('action', function($query){
                return view('google-businesses.datatable.action', ['googleBusiness'=>$query])->render();
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\GoogleBusiness $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(GoogleBusiness $model)
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
                    ->setTableId('googlebusinesses-table')
                    ->columns($this->getColumns())
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
            'company',
            'phone',
            'address',
            'website',
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
