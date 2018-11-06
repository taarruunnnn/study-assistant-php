{{-- Sidebar Holder --}}
<nav id="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('dashboard') }}"><h3>Study Assistant</h3></a>
    </div>

    <ul class="list-unstyled components">
        <li>
            <a href="{{ route('dashboard') }}">Dashboard</a>
        </li>
        <li>
            <a href="#">Schedules</a>
        </li>
        <li>
                <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">User</a>
                <ul class="collapse list-unstyled" id="pageSubmenu">
                    <li>
                        <a href="{{ route('user.edit', auth()->user()) }}">Edit User</a>
                    </li>
                </ul>
            </li>
        <li>
            <a href="#">Reports</a>
        </li>
    </ul>

</nav>