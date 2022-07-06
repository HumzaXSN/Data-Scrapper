<div class="col">
    <div class="btn-group task-list-action">
        <div class="dropdown">
            <a href="#" class="p-0 p-2 border btn btn-transparent default-color dropdown-hover" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="icon-options"></i>
            </a>
            <div class="dropdown-menu" x-placement="bottom-start"
                style="position: absolute; transform: translate3d(0px, 39px, 0px); top: 0px; left: 0px; will-change: transform;">
                <a class="dropdown-item"
                    href="{{ route('google-businesses.index', ['getJobBusinesses' => $scraperJob->id]) }}">
                    <i class="pr-2 ti-more text-muted"></i>
                    Show Businesses
                </a>
                <a class="dropdown-item" href="{{ route('scraper-criteria.exportBusiness', ['getJobBusinessesId' => $scraperJob->id, 'getScraperCriteriaDetail' => $scraperJob->scraperCriteria->keyword . ' in ' . $scraperJob->scraperCriteria->location]) }}">
                    <i class="pr-2 ti-export text-danger"></i>
                    Export Businesses
                </a>
            </div>
        </div>
    </div>
</div>
