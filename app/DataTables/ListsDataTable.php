<?php

namespace App\DataTables;

use App\Models\Lists;
use App\Models\Contact;
use App\Models\ListType;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class ListsDataTable extends DataTable
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
            ->addColumn('Type', function ($query) {
                return $query->listType->name;
            })
            ->addColumn('action', function ($query) {
                $list_type = ListType::all();
                return view('lists.datatable.action', ['list_type' => $list_type, 'list' => $query])->render();
            })
            ->addColumn('description', function ($query) {

                return '<div class="truncate">'.$query->description.'</div>';
            })
            ->addColumn('contact_count', function ($query) {
                return $query->contacts->count();;

            })
            ->addColumn('export_count', function ($query) {
                return $query->export_count;

            })
            ->addColumn('created_at', function ($query) {
                return $query->created_at->format('d-m-Y H:i:s');
            })
            ->addColumn('List From', function ($query) {
                foreach ($query->scraperCriterias as $scraperCriteria) {
                    if (isset($scraperCriteria->lists_id)) {
                        return 'Scraper';
                    }
                }
                return 'User';
            })
            ->escapeColumns([])
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\List $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Lists $model)
    {
        return $model->newQuery()->with('user')->where('list_type_id', 2);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('lists-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(0)
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
            'name',
            'description',
            'Created By' => ['data' => 'user.name', 'name' => 'user.name'],
            'Type',
            'export_count',
            'contact_count',
            'List From',
            'created_at',
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
        return 'Lists_' . date('YmdHis');
    }
}
