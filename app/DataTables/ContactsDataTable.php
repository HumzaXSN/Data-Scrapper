<?php

namespace App\DataTables;

use App\Models\Contact;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Services\DataTable;

class ContactsDataTable extends DataTable
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
            ->addColumn('flp_name', function($query){
                return $query->first_name . ' ' . $query->last_name . '
                <a href="'. $query->linkedIn_profile .'"> <img src="'.asset('assets/img/LinkedIn.png') .'" class="contact-linkedin-image"></a>';
            })
            ->addColumn('ctl_name', function($query){
                return $query->company.' '.$query->title.' '.$query->lead_status->status;
            })
            ->addColumn('csc_name', function($query){
                return $query->country.' '.$query->state.' '.$query->city.' '.$query->phone;
            })
            ->addColumn('pc_name', function($query){
                return $query->reached_platform.' '.$query->reached_count;
            })
            ->addColumn('action', function($query){
                return view('contacts.datatable.action', ['contact'=>$query])->render();
            })
            ->addColumn('checkbox', function($query){
                return '<input type="checkbox" name="contact_checkbox" data-id="'.$query['id'].'">';
            })
            ->escapeColumns([])
            ->rawColumns(['flp_name','ctl_name','csc_name','pc_name','action','checkbox']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Contact $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Contact $model)
    {
        return $model->newQuery()->with('lead_status','industry');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('contacts-table')
                    // ->columns($this->getColumns())
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
    // protected function getColumns()
    // {
    //     return [
    //         'first_name',
    //         'last_name',
    //         'title',
    //         'company',
    //         'email',
    //         'phone',
    //         'lead_status',
    //         'city',
    //         'state',
    //         'action' => [
    //             'searchable' => false,
    //             'orderable' => false
    //         ]
    //     ];
    // }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Contacts_' . date('YmdHis');
    }
}
