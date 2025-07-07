<style>
    :root {
        --sidebar-bg: #0f172a;
        --active-bg: #1e40af;
        --active-color: #e0f2fe;
        --dropdown-bg: #1e293b;
        --dropdown-border: #60a5fa;
        --nav-text: #cbd5e1;
    }

    .sidebar.bg-primary-subtle {
        background-color: var(--sidebar-bg) !important;
        transition: background-color 0.3s ease;
        border-right: 1px solid #334155;
    }

    .nav-link {
        border-radius: 0.375rem;
        transition: background-color 0.3s ease, color 0.3s ease;
        color: var(--nav-text);
    }

    .nav-link.active {
        background-color: var(--active-bg) !important;
        color: var(--active-color) !important;
        font-weight: 600;
    }

    .nav-link:hover {
        background-color: #1e3a8a;
        color: #f1f5f9;
    }

    .nav-dropdown-items {
        background-color: var(--dropdown-bg);
        border-left: 3px solid var(--dropdown-border);
        padding-left: 1rem;
    }

    .nav-item+.nav-item {
        margin-top: 0.5rem;
    }

    .nav-link i {
        margin-right: 0.5rem;
        width: 1.25rem;
        text-align: center;
    }

    /* .sidebar-minimizer {
        margin-top: 1rem;
    } */
</style>

<div class="sidebar bg-primary-subtle shadow-sm">
    <nav class="sidebar-nav">
        <ul class="nav flex-column px-3 py-2">

            @can('dashboard_access')
                <li class="nav-item">
                    <a href="{{ route('admin.home') }}" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <span>{{ trans('global.dashboard') }}</span>
                    </a>
                </li>
            @endcan

            @can('user_management_access')
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        <i class="fas fa-users nav-icon"></i>
                        <span>{{ trans('cruds.userManagement.title') }}</span>
                    </a>
                    <ul class="nav-dropdown-items">
                        @can('permission_access')
                            <li class="nav-item">
                                <a href="{{ route('admin.permissions.index') }}"
                                    class="nav-link {{ request()->is('admin/permissions*') ? 'active' : '' }}">
                                    <i class="fas fa-unlock-alt nav-icon"></i>
                                    <span>{{ trans('cruds.permission.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('role_access')
                            <li class="nav-item">
                                <a href="{{ route('admin.roles.index') }}"
                                    class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}">
                                    <i class="fas fa-user-cog nav-icon"></i>
                                    <span>{{ trans('cruds.role.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('user_access')
                            <li class="nav-item">
                                <a href="{{ route('admin.users.index') }}"
                                    class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                                    <i class="fas fa-user nav-icon"></i>
                                    <span>{{ trans('cruds.user.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('audit_log_access')
                            <li class="nav-item">
                                <a href="{{ route('admin.audit-logs.index') }}"
                                    class="nav-link {{ request()->is('admin/audit-logs*') ? 'active' : '' }}">
                                    <i class="fas fa-file-alt nav-icon"></i>
                                    <span>{{ trans('cruds.auditLog.title') }}</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan

            @foreach ([['permission' => 'status_access', 'route' => 'statuses', 'icon' => 'check-circle', 'label' => 'status'], ['permission' => 'priority_access', 'route' => 'priorities', 'icon' => 'flag', 'label' => 'priority'], ['permission' => 'category_access', 'route' => 'categories', 'icon' => 'folder', 'label' => 'category'], ['permission' => 'ticket_access', 'route' => 'tickets', 'icon' => 'ticket-alt', 'label' => 'ticket'], ['permission' => 'comment_access', 'route' => 'comments', 'icon' => 'comment', 'label' => 'comment']] as $item)
                @can($item['permission'])
                    <li class="nav-item">
                        <a href="{{ route('admin.' . $item['route'] . '.index') }}"
                            class="nav-link {{ request()->is('admin/' . $item['route'] . '*') ? 'active' : '' }}">
                            <i class="fas fa-{{ $item['icon'] }} nav-icon"></i>
                            <span>{{ trans('cruds.' . $item['label'] . '.title') }}</span>
                        </a>
                    </li>
                @endcan
            @endforeach

            <li class="nav-item">
                <a href="#" class="nav-link text-danger"
                    onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="fas fa-sign-out-alt nav-icon"></i>
                    <span>{{ trans('global.logout') }}</span>
                </a>
            </li>
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
