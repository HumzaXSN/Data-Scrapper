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
            // First Name, Last Name, LinkedIn Profile
            ->addColumn('flp_name', function($query){
                if($query->linkedIn_profile != null) {
                    return '<a class="editname">'.$query->first_name. ' ' .$query->last_name.'</a>'.
                    '<a href="'. $query->linkedIn_profile .'" target="_blank"> <img src="'.asset('assets/img/LinkedIn.png') .'" class="contact-linkedin-image"></a>';
                } else {
                    return '<a class="editname">'.$query->first_name. ' ' .$query->last_name.'</a>';
                }
            })
            // Company, Title, Lead Status
            ->addColumn('ctl_name', function($query){
                return $query->company . ' | <label class="badge bg-success"> '.$query->title.' </label> | ' .$query->lead_status->status;
            })
            // Email
            ->addColumn('email', function($query){
                return '<a href="mailto:'.$query->email.'">'.$query->email.'</a>';
            })
            //
            ->addColumn('csc_name', function($query){
                if($query->country || $query->state || $query->city || $query->phone != NULL) {
                    return $query->country . ' | ' . $query->state . ' | ' . $query->city. ' | ' . $query->phone;
                }
                else {
                    return 'No Location';
                }
                return $query->country.' | '.$query->state.' | '.$query->city.' | '.$query->phone;
            })
            ->addColumn('pc_name', function($query){
                if($query->reached_platform == null) {
                    return 'No Platfrom' .' | '.$query->reached_count;
                }
                else {
                    return $query->reached_platform .' | '.$query->reached_count;
                }
            })
            ->addColumn('action', function($query){
                return view('contacts.datatable.action', ['contact'=>$query])->render();
            })
            ->addColumn('checkbox', function($query){
                return '<input type="checkbox" name="contact_checkbox" data-id="'.$query['id'].'">';
            })
            ->escapeColumns([])
            ->rawColumns(['flp_name','ctl_name','email','csc_name','pc_name','action','checkbox']);
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
        #code...
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
