{{-- Sidebar Holder --}}
<nav id="sidebar">
    <div class="sidebar-header">
    <a href="{{ route('dashboard') }}">
        <img src="{{ asset('storage/logo.png') }}" alt="">
        <span id="logo-text">Study Assistant</span>
    </a>
    </div>

    @if (Auth::user()->isUser())
        <ul class="list-unstyled components">
            <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt sidebar-icon"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            <li class="{{ Request::is('schedules*') ? 'active' : '' }}">
                <a href="{{ route('schedules.show') }}">
                    <i class="fas fa-calendar-alt sidebar-icon"></i>
                    <span class="sidebar-text">Schedule</span>
                </a>
            </li>
            <li class="{{ Request::is('session') ? 'active' : '' }}">
                <a href="{{ route('session.show') }}">
                    <i class="fas fa-clock sidebar-icon"></i>
                    <span class="sidebar-text">Session</span>
                </a>
            </li>
            <li class="{{ Request::is('reports*') ? 'active' : '' }}">
                <a href="{{ route('reports.show') }}">
                    <i class="fas fa-chart-line sidebar-icon"></i>
                    <span class="sidebar-text">Reports</span>
                </a>
            </li>
            <li class="{{ Request::is('user/*') ? 'active' : '' }}">
                <a href="{{ route('user.edit') }}">
                    <i class="fas fa-users-cog sidebar-icon"></i>
                    <span class="sidebar-text">User</span>
                </a>
            </li>
            <li class="{{ Request::is('help*') ? 'active' : '' }}">
                <a href="{{ route('help') }}">
                    <i class="fas fa-question-circle sidebar-icon"></i>
                    <span class="sidebar-text">Help</span>
                </a>
            </li>
            
        </ul>

    
        @elseif (Auth::user()->isAdmin())
        <ul class="list-unstyled components">
            <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt sidebar-icon"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            <li class="{{ Request::is('admin/predictions') ? 'active' : '' }}">
                <a href="{{ route('admin.predictions') }}">
                    <i class="fas fa-chart-line sidebar-icon"></i>
                    <span class="sidebar-text">Predictions</span>
                </a>
            </li>
            <li class="{{ Request::is('users/*') ? 'active' : '' }}">
                <a href="{{ route('admin.users') }}">
                    <i class="fas fa-users sidebar-icon"></i>
                    <span class="sidebar-text">Users</span>
                </a>
            </li>
            <li class="{{ Request::is('user/*') ? 'active' : '' }}">
                <a href="/user/edit">
                    <i class="fas fa-users-cog sidebar-icon"></i>
                    <span class="sidebar-text">Settings</span>
                </a>
            </li>
        </ul>
    @endif
    
</nav>