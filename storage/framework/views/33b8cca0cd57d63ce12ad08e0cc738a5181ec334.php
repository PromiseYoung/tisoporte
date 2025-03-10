

<?php $__env->startSection('content'); ?>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Mostrar mensaje de éxito si existe -->
                <?php if(session('status')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('status')); ?>

                    </div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-success text-white text-center rounded-top">
                        <h5 class="mb-0">Ticket #<?php echo e($ticket->id); ?></h5>
                    </div>

                    <div class="card-body">
                        <table class="table table-hover table-striped align-middle">
                            <tbody>
                                <tr>
                                    <th><?php echo e(trans('cruds.ticket.fields.created_at')); ?></th>
                                    <td><?php echo e($ticket->created_at->format('d-m-Y H:i:s')); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(trans('cruds.ticket.fields.assigned_to_user')); ?></th>
                                    <td><?php echo e($ticket->assigned_to_user->name ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(trans('cruds.ticket.fields.title')); ?></th>
                                    <td><?php echo e($ticket->title); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(trans('cruds.ticket.fields.content')); ?></th>
                                    <td><?php echo nl2br(e($ticket->content)); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(trans('cruds.ticket.fields.attachments')); ?></th>
                                    <td>
                                        <?php $__empty_1 = true; $__currentLoopData = $ticket->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <a href="<?php echo e($attachment->getUrl()); ?>" class="text-decoration-none">
                                                <i class="fas fa-paperclip"></i> <?php echo e($attachment->file_name); ?>

                                            </a><br>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <span class="text-muted">Sin adjuntos</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo e(trans('cruds.ticket.fields.status')); ?></th>
                                    <td>
                                        <span
                                            class="badge bg-<?php echo e($ticket->status->name == 'CERRADO' ? 'danger' : 'success'); ?>">
                                            <?php echo e($ticket->status->name ?? ''); ?>

                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo e(trans('cruds.ticket.fields.category')); ?></th>
                                    <td><?php echo e($ticket->category->name); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(trans('cruds.ticket.fields.priority')); ?></th>
                                    <td><?php echo e($ticket->priority->name); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(trans('cruds.ticket.fields.localidad')); ?></th>
                                    <td><?php echo e($ticket->localidad->nombre ?? ''); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(trans('cruds.ticket.fields.author_name')); ?></th>
                                    <td><?php echo e($ticket->author_name); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(trans('cruds.ticket.fields.author_email')); ?></th>
                                    <td><?php echo e($ticket->author_email); ?></td>
                                </tr>
                            </tbody>
                        </table>

                        <h5 class="mt-4">Comentarios</h5>
                        <div class="mb-4">
                            <?php $__empty_1 = true; $__currentLoopData = $ticket->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="border rounded-3 p-3 mb-3">
                                    <p class="mb-1 fw-bold">
                                        <a href="mailto:<?php echo e($comment->author_email); ?>"
                                            class="text-decoration-none text-dark">
                                            <?php echo e($comment->author_name); ?>

                                        </a>
                                        <span class="text-muted">(<?php echo e($comment->created_at->format('d-m-Y H:i')); ?>)</span>
                                    </p>
                                    <p class="mb-0"><?php echo e($comment->comment_text); ?></p>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-muted">Sin Comentarios</p>
                            <?php endif; ?>
                        </div>

                        <form action="<?php echo e(route('tickets.storeComment', $ticket->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>

                            <?php if($errors->has('error')): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo e($errors->first('error')); ?>

                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="comment_text" class="form-label">Añadir un comentario</label>
                                <?php if($ticket->status->name != 'CERRADO'): ?>
                                    <textarea class="form-control <?php $__errorArgs = ['comment_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="comment_text" name="comment_text"
                                        rows="3" required></textarea>
                                <?php else: ?>
                                    <div class="alert alert-warning" role="alert">
                                        Este ticket está cerrado, por lo que no puedes agregar más comentarios.
                                    </div>
                                <?php endif; ?>
                                <?php $__errorArgs = ['comment_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <?php if($ticket->status->name != 'CERRADO'): ?>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-success rounded-pill">
                                        <?php echo app('translator')->get('global.submit'); ?>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tisoporte\resources\views/tickets/show.blade.php ENDPATH**/ ?>