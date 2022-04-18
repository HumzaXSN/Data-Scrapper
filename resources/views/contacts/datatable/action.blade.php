<div class="col">
    <div class="btn-group task-list-action">
        <div class="dropdown">
            <a href="#"
            class="p-0 p-2 border btn btn-transparent default-color dropdown-hover"
            data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
                <i class="icon-options"></i>
            </a>
            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 39px, 0px); top: 0px; left: 0px; will-change: transform;">
                    <a class="dropdown-item" href="{{ url('/contacts/'.$contact->id)}}">
                        <i class="pr-2 ti-eye text-primary"></i>
                        View
                    </a>
                    <a class="dropdown-item" href="{{ url('/contacts/'.$contact->id.'/edit')}}">
                        <i class="pr-2 ti-pencil-alt text-warning"></i>
                        Edit
                    </a>
                    <a class="dropdown-item" href="javascript:void(0)"
                                    data-toggle="modal"
                                    data-target=".contact-delete-modal-sm-{{$contact->id}}">
                        <i class="pr-2 icon-close text-danger"></i>
                        Delete
                    </a>
                    <a class="dropdown-item" href="{{ route('contacts.mbl',['unsubLink' => $contact->unsub_link]) }}">
                        <i class="pr-2 ti-export text-success"></i>
                        Shift to MBL
                    </a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade contact-delete-modal-sm-{{$contact->id}}"
tabindex="-1" role="dialog"
aria-labelledby="mySmallModalLabel-{{$contact->id}}" aria-hidden="true">
<div class="modal-dialog modal-md">
   <div class="modal-content">
       <div class="modal-header">
           <h6 class="modal-title" id="mySmallModalLabel">
               Delete
               <strong>{{ $contact->first_name }}</strong></h6>
           <button type="button" class="close"
                   data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
           </button>
       </div>
       <div class="modal-body">
           <h6>Are you sure?</h6><br>
           <form action="{{url('contacts/'.$contact->id)}}"
               method="post">
               @csrf
               @method('DELETE')
               <div class="float-right">
                   <button type="submit"
                           class="btn btn-primary mx-a">Yes
                   </button>
                   <button type="button"
                           class="btn btn-secondary mx-a"
                           data-dismiss="modal">No
                   </button>
               </div>

           </form>
       </div>
   </div>
</div>
</div>
