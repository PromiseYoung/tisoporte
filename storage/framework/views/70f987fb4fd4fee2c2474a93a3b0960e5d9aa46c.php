<div class="sidebar bg-dark">
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dashboard_access')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.home')); ?>" class="nav-link text-white">
                        <i class="nav-icon fas fa-fw fa-tachometer-alt"></i>
                        <?php echo e(trans('global.dashboard')); ?>

                    </a>
                </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user_management_access')): ?>
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle text-white" href="#">
                        <i class="fa-fw fas fa-users nav-icon"></i>
                        <?php echo e(trans('cruds.userManagement.title')); ?>

                    </a>
                    <ul class="nav-dropdown-items">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('permission_access')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('admin.permissions.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : ''); ?> text-white">
                                    <i class="fa-fw fas fa-unlock-alt nav-icon"></i>
                                    <?php echo e(trans('cruds.permission.title')); ?>

                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role_access')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('admin.roles.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : ''); ?> text-white">
                                    <i class="fa-fw fas fa-user-cog nav-icon"></i>
                                    <?php echo e(trans('cruds.role.title')); ?>

                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user_access')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('admin.users.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : ''); ?> text-white">
                                    <i class="fa-fw fas fa-user nav-icon"></i>
                                    <?php echo e(trans('cruds.user.title')); ?>

                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('audit_log_access')): ?>
                            <li class="nav-item">
                                <a href="<?php echo e(route('admin.audit-logs.index')); ?>"
                                    class="nav-link <?php echo e(request()->is('admin/audit-logs') || request()->is('admin/audit-logs/*') ? 'active' : ''); ?> text-white">
                                    <i class="fa-fw fas fa-file-alt nav-icon"></i>
                                    <?php echo e(trans('cruds.auditLog.title')); ?>

                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('status_access')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.statuses.index')); ?>"
                        class="nav-link <?php echo e(request()->is('admin/statuses') || request()->is('admin/statuses/*') ? 'active' : ''); ?> text-white">
                        <i class="fa-fw fas fa-check-circle nav-icon"></i>
                        <?php echo e(trans('cruds.status.title')); ?>

                    </a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('priority_access')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.priorities.index')); ?>"
                        class="nav-link <?php echo e(request()->is('admin/priorities') || request()->is('admin/priorities/*') ? 'active' : ''); ?> text-white">
                        <i class="fa-fw fas fa-flag nav-icon"></i>
                        <?php echo e(trans('cruds.priority.title')); ?>

                    </a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category_access')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.categories.index')); ?>"
                        class="nav-link <?php echo e(request()->is('admin/categories') || request()->is('admin/categories/*') ? 'active' : ''); ?> text-white">
                        <i class="fa-fw fas fa-folder nav-icon"></i>
                        <?php echo e(trans('cruds.category.title')); ?>

                    </a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ticket_access')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.tickets.index')); ?>"
                        class="nav-link <?php echo e(request()->is('admin/tickets') || request()->is('admin/tickets/*') ? 'active' : ''); ?> text-white">
                        <i class="fa-fw fas fa-ticket-alt nav-icon"></i>
                        <?php echo e(trans('cruds.ticket.title')); ?>

                    </a>
                </li>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('comment_access')): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.comments.index')); ?>"
                        class="nav-link <?php echo e(request()->is('admin/comments') || request()->is('admin/comments/*') ? 'active' : ''); ?> text-white">
                        <i class="fa-fw fas fa-comment nav-icon"></i>
                        <?php echo e(trans('cruds.comment.title')); ?>

                    </a>
                </li>
            <?php endif; ?>

            <li class="nav-item">
                <a href="#" class="nav-link text-white"
                    onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="nav-icon fas fa-fw fa-sign-out-alt"></i>
                    <?php echo e(trans('global.logout')); ?>

                </a>
            </li>
        </ul>

    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
<?php /**PATH C:\wamp64\www\tisoporte\resources\views/partials/menu.blade.php ENDPATH**/ ?>