{{-- Sidebar Holder --}}
<nav id="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('dashboard') }}"><h3>Study Assistant</h3></a>
    </div>

    <ul class="list-unstyled components">
        <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}">Dashboard</a>
        </li>
        <li class="{{ Request::is('schedules/*') ? 'active' : '' }}">
            <a href="/schedules">Schedules</a>
        </li>
        <li class="{{ Request::is('user/*') ? 'active' : '' }}">
            <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">User</a>
            <ul class="collapse list-unstyled" id="pageSubmenu">
                <li class="{{ Request::is('user/edit') ? 'active' : '' }}">
                    <a href="/user/edit">Edit User</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ route('reports.show') }}">Reports</a>
        </li>
    </ul>
    <div id="CTA">
        <ul class="list-unstyled">
            <li>
                <a href="/session" class="btn btn-success">START STUDY SESSION</a>
            </li>
        </ul>
    </div>
    

</nav>