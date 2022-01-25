    <!--===========header start===========-->
    <header class="app-header navbar">

        <!--brand start-->
        <div class="navbar-brand">
            <a class="" href="{{ url('/') }}">
                <img src="assets/img/ashlar.png" srcset="assets/img/ashlar@2x.png 2x" alt="Ashlar Logo" class="header-logo">
            </a>
        </div>
        <!--brand end-->

        <!--left side nav toggle start-->
        <ul class="mr-auto nav navbar-nav">
            <li class="nav-item d-lg-none">
                <button class="navbar-toggler mobile-leftside-toggler" type="button"><i class="ti-align-right"></i></button>
            </li>
            <li class="nav-item d-md-down-none">
                <a class="nav-link navbar-toggler left-sidebar-toggler" href="#"><i class=" ti-align-right"></i></a>
            </li>
        </ul>
        <!--left side nav toggle end-->

        <!--right side nav start-->
        <ul class="ml-auto nav navbar-nav">

            <li class="nav-item dropdown dropdown-slide">
                <a class="nav-link nav-pill user-avatar" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ asset('assets/img/user.png') }}" alt="John Doe">
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-accout">
                    <div class="pb-3 dropdown-header">
                        <div class="media d-user">
                            <img class="mr-3 align-self-center" src="{{ asset('assets/img/user.png') }}" alt="John Doe">
                            <div class="media-body">
                                <h5 class="mt-0 mb-0">{{ Auth::user()->name}}</h5>
                                <span>{{ Auth::user()->email}}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('logout') }}" class="dropdown-item"
                    onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                        <i class=" ti-unlock"></i>
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>

        <!--right side nav end-->
    </header>
    <!--===========header end===========-->
