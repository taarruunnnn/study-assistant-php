{{-- Sidebar Holder --}}
<nav id="sidebar">
    <div class="sidebar-header">
    <a href="{{ route('dashboard') }}">
        <img src="{{ asset('storage/logo.png') }}" alt="">
        <span id="logo-text">Study Assistant</span>
    </a>
    </div>

    <ul class="list-unstyled components">
        <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt sidebar-icon"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
        </li>
        <li class="{{ Request::is('schedules*') ? 'active' : '' }}">
            <a href="/schedules">
                <i class="fas fa-calendar-alt sidebar-icon"></i>
                <span class="sidebar-text">Schedule</span>
            </a>
        </li>
        <li class="{{ Request::is('reports*') ? 'active' : '' }}">
            <a href="{{ route('reports.show') }}">
                <i class="fas fa-chart-line sidebar-icon"></i>
                <span class="sidebar-text">Reports</span>
            </a>
        </li>
        <li class="{{ Request::is('user/*') ? 'active' : '' }}">
            <a href="/user/edit">
                <i class="fas fa-users-cog sidebar-icon"></i>
                <span class="sidebar-text">User</span>
            </a>
        </li>
        
    </ul>
    <div id="CTA">
        <ul class="list-unstyled">
            <li class="{{ Request::is('session/*') ? 'active' : '' }}">
                <a href="/session">
                    <i class="fas fa-clock sidebar-icon"></i>
                    <span class="sidebar-text">Session</span>
                </a>
            </li>
        </ul>
    </div>
    

</nav>