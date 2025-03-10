<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <?php echo e(trans('global.show')); ?> <?php echo e(trans('cruds.auditLog.title')); ?>

        </div>

        <div class="card-body">
            <div class="mb-2">
                <table class="table table-bordered table-striped table-hover">
                    <tbody>
                        <tr>
                            <th>
                                <?php echo e(trans('cruds.auditLog.fields.id')); ?>

                            </th>
                            <td>
                                <?php echo e($auditLog->id); ?>

                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php echo e(trans('cruds.auditLog.fields.description')); ?>

                            </th>
                            <td>
                                <?php echo e($auditLog->description); ?>

                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php echo e(trans('cruds.auditLog.fields.subject_id')); ?>

                            </th>
                            <td>
                                <?php echo e($auditLog->subject_id); ?>

                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php echo e(trans('cruds.auditLog.fields.subject_type')); ?>

                            </th>
                            <td>
                                <?php echo e($auditLog->subject_type); ?>

                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php echo e(trans('cruds.auditLog.fields.user_id')); ?>

                            </th>
                            <td>
                                <?php echo e($auditLog->user_id); ?>

                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php echo e(trans('cruds.auditLog.fields.properties')); ?>

                            </th>
                            <td>
                                <pre><?php echo e(json_encode(json_decode($auditLog->properties), JSON_PRETTY_PRINT)); ?></pre>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php echo e(trans('cruds.auditLog.fields.host')); ?>

                            </th>
                            <td>
                                <?php echo e($auditLog->host); ?>

                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php echo e(trans('cruds.auditLog.fields.created_at')); ?>

                            </th>
                            <td>
                                <?php echo e($auditLog->created_at->format('d-m-Y H:i')); ?> <!-- Formatea la fecha -->
                            </td>
                        </tr>
                    </tbody>
                </table>
                <a style="margin-top:20px;" class="btn btn-secondary" href="<?php echo e(url()->previous()); ?>">
                    <?php echo e(trans('global.back_to_list')); ?>

                </a>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tisoporte\resources\views/admin/auditLogs/show.blade.php ENDPATH**/ ?>