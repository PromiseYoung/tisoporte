<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><?php echo e(trans('global.create')); ?> <?php echo e(trans('cruds.priority.title_singular')); ?></h5>
        </div>

        <div class="card-body">
            <form action="<?php echo e(route('admin.priorities.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <div class="form-group <?php echo e($errors->has('name') ? 'has-error' : ''); ?>">
                    <label for="name"><?php echo e(trans('cruds.priority.fields.name')); ?>*</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="<?php echo e(old('name', isset($priority) ? $priority->name : '')); ?>" required>
                    <?php if($errors->has('name')): ?>
                        <div class="invalid-feedback d-block">
                            <?php echo e($errors->first('name')); ?>

                        </div>
                    <?php endif; ?>
                    <small class="form-text text-muted">
                        <?php echo e(trans('cruds.priority.fields.name_helper')); ?>

                    </small>
                </div>

                <div class="form-group <?php echo e($errors->has('color') ? 'has-error' : ''); ?>">
                    <label for="color"><?php echo e(trans('cruds.priority.fields.color')); ?></label>
                    <input type="text" id="color" name="color" class="form-control colorpicker"
                        value="<?php echo e(old('color', isset($priority) ? $priority->color : '')); ?>">
                    <?php if($errors->has('color')): ?>
                        <div class="invalid-feedback d-block">
                            <?php echo e($errors->first('color')); ?>

                        </div>
                    <?php endif; ?>
                    <small class="form-text text-muted">
                        <?php echo e(trans('cruds.priority.fields.color_helper')); ?>

                    </small>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" type="submit">
                        <?php echo e(trans('global.save')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css"
        rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js">
    </script>
    <script>
        $(document).ready(function() {
            $('.colorpicker').colorpicker();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tisoporte\resources\views/admin/priorities/create.blade.php ENDPATH**/ ?>