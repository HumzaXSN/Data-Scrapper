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
                    <a class="dropdown-item" href="{{ url('/scraper-criterias/'.$scraperCriteria->id)}}">
                        <i class="pr-2 ti-eye text-primary"></i>
                        View
                    </a>
                    <a class="dropdown-item" data-toggle="modal" data-target="#myEdit{{ $scraperCriteria->id }}">
                        <i class="pr-2 ti-pencil-alt text-warning"></i>
                        Edit
                    </a>
                    <a class="dropdown-item" href="javascript:void(0)"
                                    data-toggle="modal"
                                    data-target=".contact-delete-modal-sm-{{$scraperCriteria->id}}">
                        <i class="pr-2 icon-close text-danger"></i>
                        Change Status
                    </a>
            </div>
        </div>
    </div>
</div>

{{-- Update Data --}}
<div class="modal fade" id="myEdit{{$scraperCriteria->id}}">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('scraper-criterias.update',$scraperCriteria->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Update List</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <strong>Keyword:</strong>
                    <input class="form-control" type="text" name="keyword" placeholder="Enter keyword"
                        value="{{ $scraperCriteria->keyword }}" required>
                    <strong>Location:</strong>
                    <input class="form-control" type="text" name="location" placeholder="Enter Status"
                        value="{{ $scraperCriteria->location }}" required>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade contact-delete-modal-sm-{{$scraperCriteria->id}}"
tabindex="-1" role="dialog"
aria-labelledby="mySmallModalLabel-{{$scraperCriteria->id}}" aria-hidden="true">
<div class="modal-dialog modal-md">
   <div class="modal-content">
       <div class="modal-header">
           <h6 class="modal-title" id="mySmallModalLabel">
               Change Status
               <strong>{{ $scraperCriteria->first_name }}</strong></h6>
           <button type="button" class="close"
                   data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
           </button>
       </div>
       <div class="modal-body">
           <h6>Are you sure?</h6><br>
           <form action="{{url('scraper-criterias/'.$scraperCriteria->id)}}"
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
