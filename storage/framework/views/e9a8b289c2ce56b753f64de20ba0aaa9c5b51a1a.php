<?php $__env->startSection('content'); ?>
    <style>
        /* Proccesing default datatables */
        .dataTables_processing {
            top: 50% !important;
            left: 50% !important;
            width: auto !important;
            background: transparent !important;
            border: none !important;
            transform: translate(-50%, -50%);
            z-index: 9999;
            text-align: center;
            padding: 20px;
        }

        /* ESPINER LOADER VIEW TICKETS */
        .spinner-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 10px;
        }

        @keyframes  spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ticket_create')): ?>
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="<?php echo e(route('admin.tickets.create')); ?>">
                    <?php echo e(trans('global.add')); ?> <?php echo e(trans('cruds.ticket.title_singular')); ?>

                </a>
            </div>
        </div>
    <?php endif; ?>
    <div class="card">
        <div class="card-header">
            <?php echo e(trans('cruds.ticket.title_singular')); ?> <?php echo e(trans('global.list')); ?>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table
                    class="table table-striped table-hover table-bordered table-sm dt-responsive nowrap ajaxTable datatable datatable-Ticket">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="10">
                                <i class="fas fa-check-square"></i>
                            </th>
                            <th>
                                <i class="fas fa-hashtag"></i> <?php echo e(trans('cruds.ticket.fields.id')); ?>

                            </th>
                            <th>
                                <i class="fas fa-heading"></i> <?php echo e(trans('cruds.ticket.fields.title')); ?>

                            </th>
                            <th>
                                <i class="fas fa-info-circle"></i> <?php echo e(trans('cruds.ticket.fields.status')); ?>

                            </th>
                            <th>
                                <i class="fas fa-exclamation-triangle"></i> <?php echo e(trans('cruds.ticket.fields.priority')); ?>

                            </th>
                            <th>
                                <i class="fas fa-tags"></i> <?php echo e(trans('cruds.ticket.fields.category')); ?>

                            </th>
                            <th>
                                <i class="fas fa-map-marker-alt"></i> <?php echo e(trans('cruds.ticket.fields.localidad')); ?>

                            </th>
                            <th>
                                <i class="fas fa-user"></i> <?php echo e(trans('cruds.ticket.fields.author_name')); ?>

                            </th>
                            <th>
                                <i class="fas fa-envelope"></i> <?php echo e(trans('cruds.ticket.fields.author_email')); ?>

                            </th>
                            <th>
                                <i class="fas fa-user-check"></i> <?php echo e(trans('cruds.ticket.fields.assigned_to_user')); ?>

                            </th>
                            <th>
                                <i class="fas fa-cogs"></i> &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated by DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('scripts'); ?>
    <script>
        $(document).ready(function() {
            // Filtros HTML
            let filters = `
<form class="form-inline" id="filtersForm">
  <div class="form-group mx-2 mb-2">
    <select class="form-control" name="status">
      <option value="">Estados</option>
      <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($status->id); ?>"<?php echo e(request('status') == $status->id ? 'selected' : ''); ?>><?php echo e($status->name); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  <div class="form-group mx-2 mb-2">
    <select class="form-control" name="priority">
      <option value="">Prioridades</option>
      <?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priority): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($priority->id); ?>"<?php echo e(request('priority') == $priority->id ? 'selected' : ''); ?>><?php echo e($priority->name); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  <div class="form-group mx-2 mb-2">
    <select class="form-control" name="category">
      <option value="">Categorias</option>
      <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($category->id); ?>"<?php echo e(request('category') == $category->id ? 'selected' : ''); ?>><?php echo e($category->name); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>
</form>
`;

            let dtButtons = [];

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ticket_delete')): ?>
                let deleteButtonTrans = '<?php echo e(trans('global.datatables.delete')); ?>';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "<?php echo e(route('admin.tickets.massDestroy')); ?>",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).data(), function(entry) {
                            return entry.id;
                        });

                        if (ids.length === 0) {
                            alert('<?php echo e(trans('global.datatables.zero_selected')); ?>');
                            return;
                        }

                        if (confirm('<?php echo e(trans('global.areYouSure')); ?>')) {
                            $.ajax({
                                headers: {
                                    'x-csrf-token': _token
                                },
                                method: 'POST',
                                url: config.url,
                                data: {
                                    ids: ids,
                                    _method: 'DELETE'
                                }
                            }).done(function() {
                                location.reload();
                            });
                        }
                    }
                };
                dtButtons.push(deleteButton);
            <?php endif; ?>

            let searchParams = new URLSearchParams(window.location.search);
            let dtOverrideGlobals = {
                buttons: dtButtons,
                serverSide: true,
                processing: true,
                retrieve: true,
                aaSorting: [],
                ajax: {
                    url: "<?php echo e(route('admin.tickets.index')); ?>",
                    data: function(d) {
                        d.status = $('select[name="status"]').val();
                        d.priority = $('select[name="priority"]').val();
                        d.category = $('select[name="category"]').val();
                    }
                },
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'title',
                        name: 'title',
                        render: function(data, type, row) {
                            return '<a href="' + row.view_link + '">' + data + ' (' + row
                                .comments_count + ')</a>';
                        }
                    },
                    {
                        data: 'status_name',
                        name: 'status.name',
                        render: function(data, type, row) {
                            return '<span style="color:' + row.status_color + '">' + data + '</span>';
                        }
                    },
                    {
                        data: 'priority_name',
                        name: 'priority.name',
                        render: function(data, type, row) {
                            return '<span style="color:' + row.priority_color + '">' + data + '</span>';
                        }
                    },
                    {
                        data: 'category_name',
                        name: 'category.name',
                        render: function(data, type, row) {
                            return '<span style="color:' + row.category_color + '">' + data + '</span>';
                        }
                    },
                    {
                        data: 'localidad_nombre',
                        name: 'localidad_nombre'
                    },
                    {
                        data: 'author_name',
                        name: 'author_name'
                    },
                    {
                        data: 'author_email',
                        name: 'author_email'
                    },
                    {
                        data: 'assigned_to_user_name',
                        name: 'assigned_to_user.name'
                    },
                    {
                        data: 'actions',
                        name: '<?php echo e(trans('global.actions')); ?>'
                    }
                ],
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
                initComplete: function(settings, json) {
                    $(".dataTables_filter").after(filters);
                },
                language: {
                    processing: '<div class="spinner-container"><div class="spinner"></div><p style="margin-top: 30px;">Cargando datos...</p></div>'
                }
            };

            // Inicializa la DataTable
            let table = $('.datatable-Ticket').DataTable(dtOverrideGlobals);

            table.on('preXhr.dt', function() {
                $('.spinner-container').show();
            });
            // Maneja el cambio en los select para recargar la tabla
            $('.card-body').on('change', 'select', function() {
                table.ajax.reload(); // Recarga la tabla con los nuevos parámetros
            });

            // Ajustar columnas en el cambio de pestaña
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                table.columns.adjust();
            });
            setInterval(function() {
                table.ajax.reload(null, false); // Recarga la tabla sin reiniciar la paginación
            }, 10000); // Cada 15 segundos
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tisoporte\resources\views/admin/tickets/index.blade.php ENDPATH**/ ?>