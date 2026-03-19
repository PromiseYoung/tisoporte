<style>
    :root {
        --sidebar-bg: #0f172a;
        --sidebar-hover: #1e293b;
        --sidebar-active: #2563eb;
        --sidebar-text: #cbd5e1;
        --sidebar-muted: #94a3b8;
        --sidebar-border: #1e293b;
    }

    .sidebar {
        background: var(--sidebar-bg) !important;
        border-right: 1px solid var(--sidebar-border);
        min-height: 100vh;
    }

    /* LINKS BASE */
    .sidebar .nav-link {
        color: var(--sidebar-text);
        border-radius: 0.5rem;
        padding: 0.55rem 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    /* ICONOS */
    .sidebar .nav-link i {
        width: 20px;
        text-align: center;
        color: var(--sidebar-muted);
        transition: color 0.2s ease;
    }

    /* HOVER */
    .sidebar .nav-link:hover {
        background: var(--sidebar-hover);
        color: #f1f5f9;
    }

    .sidebar .nav-link:hover i {
        color: #e2e8f0;
    }

    /* ACTIVE */
    .sidebar .nav-link.active {
        background: var(--sidebar-active) !important;
        color: #fff !important;
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
    }

    .sidebar .nav-link.active i {
        color: #fff;
    }

    /* DROPDOWN */
    .nav-dropdown-items {
        background: transparent;
        border-left: 2px solid var(--sidebar-border);
        margin-left: 0.5rem;
        padding-left: 0.75rem;
    }

    /* SUBITEMS */
    .nav-dropdown-items .nav-link {
        font-size: 0.9rem;
        color: var(--sidebar-muted);
    }

    .nav-dropdown-items .nav-link:hover {
        color: #e2e8f0;
    }

    /* LOGOUT */
    .nav-link.text-danger {
        color: #f87171 !important;
    }

    .nav-link.text-danger:hover {
        background: rgba(248, 113, 113, 0.1);
        color: #fecaca !important;
    }

    /* SCROLL (si el menú crece) */
    .sidebar-nav {
        max-height: 100vh;
        overflow-y: auto;
    }

    .sidebar-nav::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-nav::-webkit-scrollbar-thumb {
        background: #334155;
        border-radius: 10px;
    }
</style>

<div class="sidebar bg-primary-subtle shadow-sm">
    <nav class="sidebar-nav">
        <ul class="nav flex-column px-3 py-2">

            @can('dashboard_access')
                <li class="nav-item mb-1">
                    <a href="{{ route('admin.home') }}" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <span>{{ trans('global.dashboard') }}</span>
                    </a>
                </li>
            @endcan

            @can('user_management_access')
                <li class="nav-item mb-1 nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        <i class="fas fa-users nav-icon"></i>
                        <span>{{ trans('cruds.userManagement.title') }}</span>
                    </a>
                    <ul class="nav-dropdown-items">
                        @can('permission_access')
                            <li class="nav-item mb-1">
                                <a href="{{ route('admin.permissions.index') }}"
                                    class="nav-link {{ request()->is('admin/permissions*') ? 'active' : '' }}">
                                    <i class="fas fa-unlock-alt nav-icon"></i>
                                    <span>{{ trans('cruds.permission.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('role_access')
                            <li class="nav-item mb-1">
                                <a href="{{ route('admin.roles.index') }}"
                                    class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}">
                                    <i class="fas fa-user-cog nav-icon"></i>
                                    <span>{{ trans('cruds.role.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('user_access')
                            <li class="nav-item mb-1">
                                <a href="{{ route('admin.users.index') }}"
                                    class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                                    <i class="fas fa-user nav-icon"></i>
                                    <span>{{ trans('cruds.user.title') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('audit_log_access')
                            <li class="nav-item mb-1">
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
                    <li class="nav-item mb-1">
                        <a href="{{ route('admin.' . $item['route'] . '.index') }}"
                            class="nav-link {{ request()->is('admin/' . $item['route'] . '*') ? 'active' : '' }}">
                            <i class="fas fa-{{ $item['icon'] }} nav-icon"></i>
                            <span>{{ trans('cruds.' . $item['label'] . '.title') }}</span>
                        </a>
                    </li>
                @endcan
            @endforeach

            <li class="nav-item mb-1">
                <a href="#" class="nav-link text-danger"
                    onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="fas fa-sign-out-alt nav-icon"></i>
                    <span>{{ trans('global.logout') }}</span>
                </a>
            </li>
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button" title="menu"></button>
</div>
