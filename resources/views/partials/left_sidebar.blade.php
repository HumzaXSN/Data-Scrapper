        <!--left sidebar start-->
        <div class="left-sidebar">
            <nav class="sidebar-menu">
                <ul id="nav-accordion">
                    <li>
                        <a href="{{ route('contacts.index') }}">
                            <i class="fa fa-dot-circle-o text-primary"></i>
                            <span>Contacts</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('lists.index') }}">
                            <i class="fa fa-dot-circle-o text-primary"></i>
                            <span>Lists</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('google-businesses.index') }}">
                            <i class="fa fa-dot-circle-o text-primary"></i>
                            <span>Businesses</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('scraper-criterias.index') }}">
                            <i class="fa fa-dot-circle-o text-primary"></i>
                            <span>Scraper Criteria</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contacts.index',['list' => 1]) }}">
                            <i class="fa fa-dot-circle-o text-primary"></i>
                            <span>Master Block List</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <!--left sidebar end-->
