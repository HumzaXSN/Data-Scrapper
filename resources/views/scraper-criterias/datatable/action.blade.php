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
                @if ($scraperCriteria->status == 'Completed')
                    <a class="dropdown-item" href="#" disabled>
                        <i class="pr-2 ti-alert"></i>
                        Scraper Completed
                @else
                    <a onclick="disableButton()" class="dropdown-item btnForce"
                        href="{{ route('scraper-criterias.startScraper', ['id' => $scraperCriteria->id]) }}">
                        <i class="pr-2 ti-reload"></i>
                        Force Start Scrapper
                    </a>
                @endif
                @if ($scraperCriteria->status == 'In-Active')
                    <a class="dropdown-item" href="{{ route('scraper-criterias.runScraper', ['id' => $scraperCriteria->id]) }}">
                        <i class="pr-2 ti-loop text-secondary"></i>
                        Activate Scraper
                    </a>
                @endif
                @if ($scraperCriteria->status == 'Active')
                    <a class="dropdown-item" href="{{ route('scraper-criterias.stopScraper', ['id' => $scraperCriteria->id]) }}">
                        <i class="pr-2 icon-close text-danger"></i>
                        In-Activate Scraper
                    </a>
                @endif
                <a class="dropdown-item" href="{{ route('scraper-jobs.index', ['id' => $scraperCriteria->id]) }}">
                    <i class="pr-2 ti-menu text-info"></i>
                    Show Jobs
                </a>
                <a class="dropdown-item" href="{{ route('google-businesses.index', ['showBusiness' => $scraperCriteria->id]) }}">
                    <i class="pr-2 ti-more text-muted"></i>
                    Show Businesses
                </a>
                <a class="dropdown-item" href="{{ route('scraper-criteria.exportBusiness', ['getCriteriaId' => $scraperCriteria->id, 'getCriteriaDetail' => $scraperCriteria->keyword . ' in ' . $scraperCriteria->location]) }}">
                    <i class="pr-2 ti-export text-danger"></i>
                    Export Businesses
                </a>
                @if ($scraperCriteria->lists_id != null)
                    <a class="dropdown-item"
                        href="{{ route('contacts.index', ['list' => $scraperCriteria->lists_id]) }}">
                        <i class="pr-2 ti-layout-grid2-thumb text-success"></i>
                        Show List
                    </a>
                @endif
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
                    <strong>Limit:</strong>
                    <input class="form-control" type="text" name="limit" placeholder="Enter Limit"
                        value="{{ $scraperCriteria->limit }}" required>
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

<script>
    function disableButton() {
        $('.btnForce').addClass('d-none');
        alert('Please wait while scraper is running');
    }
</script>
