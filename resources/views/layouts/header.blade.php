{{-- Navigation Bar --}}
<nav class="navbar navbar-expand-md fixed-top navbar-light bg-light" id="navigation">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"> @yield('title')</a>
        
        <button class="btn btn-primary d-inline-block d-lg-none ml-auto" type="button" id="sidebarCollapse">
            <i class="fas fa-align-justify"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav navbar-nav ml-auto">
                <li class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @if (Auth::user()->gender == 'M')
                            <img src="{{ asset('storage/images/header_m.png') }}" alt="" srcset="">
                        @else
                            <img src="{{ asset('storage/images/header_f.png') }}" alt="" srcset="">
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-right" id="user-dropdown" role="menu">
                        <li id="user-li">
                            {{ Auth::user()->name }}
                        </li>
                        <li>
                            <a href="{{ route('user.profile') }}">Profile</a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        
    </div>
</nav>