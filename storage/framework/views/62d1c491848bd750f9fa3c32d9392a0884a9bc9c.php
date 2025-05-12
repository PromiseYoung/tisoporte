<?php $__env->startSection('content'); ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center dark-mode-header">
            <h5 class="mb-0"><?php echo e(trans('global.show')); ?> <?php echo e(trans('cruds.ticket.title')); ?></h5>
            <a class="btn btn-light btn-sm dark-mode-btn" href="<?php echo e(route('admin.tickets.index')); ?>">
                <i class="fas fa-arrow-left"></i> <?php echo e(trans('global.back_to_list')); ?>

            </a>
        </div>

        <div class="card-body dark-mode-body">
            <?php if(session('status')): ?>
                <div class="alert alert-success alert-dismissible fade show dark-mode-alert" role="alert">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered table-striped dark-mode-table">
                    <tbody>
                        <tr>
                            <th class="dark-mode-th"><?php echo e(trans('cruds.ticket.fields.id')); ?></th>
                            <td class="dark-mode-td"><?php echo e($ticket->id); ?></td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th"><?php echo e(trans('cruds.ticket.fields.created_at')); ?></th>
                            <td class="dark-mode-td"><?php echo e($ticket->created_at->locale('es')->format('d-m-Y H:i:s A')); ?></td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th"><?php echo e(trans('cruds.ticket.fields.title')); ?></th>
                            <td class="dark-mode-td"><?php echo e($ticket->title); ?></td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th"><?php echo e(trans('cruds.ticket.fields.content')); ?></th>
                            <td class="dark-mode-td"><?php echo $ticket->content; ?></td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th"><?php echo e(trans('cruds.ticket.fields.attachments')); ?></th>
                            <td class="dark-mode-td">
                                <?php $__currentLoopData = $ticket->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e($attachment->getUrl()); ?>" target="_blank"
                                        class="text-primary d-block dark-mode-link"><i class="fas fa-paperclip"></i>
                                        <?php echo e($attachment->file_name); ?></a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th"><?php echo e(trans('cruds.ticket.fields.status')); ?></th>
                            <td class="dark-mode-td"><span
                                    class="badge bg-info dark-mode-badge"><?php echo e($ticket->status->name ?? ''); ?></span></td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th"><?php echo e(trans('cruds.ticket.fields.priority')); ?></th>
                            <td class="dark-mode-td"><span
                                    class="badge bg-warning dark-mode-badge"><?php echo e($ticket->priority->name ?? ''); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th"><?php echo e(trans('cruds.ticket.fields.category')); ?></th>
                            <td class="dark-mode-td"><?php echo e($ticket->category->name ?? ''); ?></td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th"><?php echo e(trans('cruds.ticket.fields.localidad')); ?></th>
                            <td class="dark-mode-td"><?php echo e($ticket->localidad->nombre ?? ''); ?></td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th"><?php echo e(trans('cruds.ticket.fields.author_name')); ?></th>
                            <td class="dark-mode-td"><?php echo e($ticket->author_name); ?></td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th"><?php echo e(trans('cruds.ticket.fields.author_email')); ?></th>
                            <td class="dark-mode-td"><?php echo e($ticket->author_email); ?></td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th"><?php echo e(trans('cruds.ticket.fields.assigned_to_user')); ?></th>
                            <td class="dark-mode-td"><?php echo e($ticket->assigned_to_user->name ?? ''); ?></td>
                        </tr>
                        <tr>
                            <th class="dark-mode-th"><?php echo e(trans('cruds.ticket.fields.comments')); ?></th>
                            <td class="dark-mode-td">
                                <?php $__empty_1 = true; $__currentLoopData = $ticket->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="row mb-3 dark-mode-comment">
                                        <div class="col">
                                            <p class="fw-bold mb-1">
                                                <a href="mailto:<?php echo e($comment->author_email); ?>"
                                                    class="dark-mode-link"><?php echo e($comment->author_name); ?></a>
                                                <small
                                                    class="text-muted">(<?php echo e($comment->created_at->locale('es')->format('d-m-Y H:i:s A')); ?>)</small>
                                            </p>
                                            <p class="mb-0"><?php echo e($comment->comment_text); ?></p>
                                        </div>
                                    </div>
                                    <hr class="dark-mode-hr" />
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <p class="text-muted">Sin Comentarios</p>
                                <?php endif; ?>
                                <form action="<?php echo e(route('admin.tickets.storeComment', $ticket->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <label for="comment_text" class="form-label">Escribe un comentario</label>
                                        <textarea class="form-control dark-mode-textarea" id="comment_text" name="comment_text" rows="3" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-2 dark-mode-btn">
                                        <i class="fas fa-paper-plane"></i> <?php echo app('translator')->get('global.submit'); ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 d-flex justify-content-end">
                <a href="<?php echo e(route('admin.tickets.edit', $ticket->id)); ?>" class="btn btn-secondary me-2 dark-mode-btn">
                    <i class="fas fa-edit"></i> <?php echo app('translator')->get('global.edit'); ?> <?php echo app('translator')->get('cruds.ticket.title_singular'); ?>
                </a>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tisoporte\resources\views/admin/tickets/show.blade.php ENDPATH**/ ?>