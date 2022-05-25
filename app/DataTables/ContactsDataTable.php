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
                return '<a class="editcompany">'. $query->company . ' | <label class="badge bg-success"> '.$query->title.' </label> | ' .$query->lead_status->status .'</a>';
            })
            // Email
            ->addColumn('emailLink', function($query){
                return '<a href="mailto:'.$query->email.'">'.$query->email.'</a>';
            })
            // Lead Country
            ->addColumn('csc_name', function($query){
                if($query->country || $query->state || $query->city || $query->phone != NULL) {
                    return '<a class="editcountry">'. $query->country . ' | ' . $query->state . ' | ' . $query->city. ' | ' . $query->phone . '</a>';
                }
                else {
                    return '<a class="editcountry">'.'No Location'.'</a>';
                }
            })
            // Count & Platform
            ->addColumn('pc_name', function($query){
                if($query->reached_platform == null) {
                    return '<a class="editplatform">'.'No Platfrom' .' | '.$query->reached_count.'</a>';
                }
                else {
                    return '<a class="editplatform">' .$query->reached_platform .' | '.$query->reached_count.'</a>';
                }
            })
            ->addColumn('industry', function($query) {
                return '<a class="editindsutry">' . $query->industry->name . '</a>';
            })
            ->addColumn('created_at', function ($query) {
                return $query->created_at->format('d-m-Y H:i:s');
            })
            ->addColumn('action', function($query){
                return view('contacts.datatable.action', ['contact'=>$query])->render();
            })
            ->addColumn('checkbox', function($query){
                return '<input type="checkbox" name="contact_checkbox" data-id="'.$query['id'].'">';
            })
            ->setRowClass(function ($query) {
                if(isset($query->list_id))
                    return $query->list_id == 1 ? 'alert-danger' : '';
                else
                    return '';
            })
            ->escapeColumns([])
            ->rawColumns(['flp_name','ctl_name','emailLink','csc_name','pc_name','industry','action','checkbox']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Contact $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Contact $model)
    {
        $startDate = $this->startDate;
        $endDate = $this->endDate;
        $getList = $this->getList;
        if(empty($startDate) && $getList == null) {
            return $model->newQuery()->with('lead_status', 'industry');
        } elseif(empty($startDate) && $getList != null) {
            return $model->newQuery()->with('lead_status', 'industry')->where('list_id', $getList);
        } elseif(!empty($startDate) && $getList == null) {
            return $model->newQuery()->with('lead_status', 'industry')->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"]);
        } elseif(!empty($startDate) && $getList != null) {
            return $model->newQuery()->with('lead_status', 'industry')->whereBetween('created_at', [$startDate . " 00:00:00", $endDate . " 23:59:59"])->where('list_id', $getList);
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
                    ->setTableId('contacts-table')
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
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Contacts_' . date('YmdHis');
    }
}
