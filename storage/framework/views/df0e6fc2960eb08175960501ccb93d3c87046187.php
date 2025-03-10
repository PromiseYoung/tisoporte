
<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center align-items-center min-vh-100 bg-light">
        <div class="col-md-8 col-lg-6 col-xl-4">
            <div class="card border-0 shadow-lg rounded-lg">
                <div class="card-body p-5">
                    <h1 class="text-center mb-4 text-primary"><?php echo e(trans('panel.site_title')); ?></h1>

                    <p class="text-muted text-center mb-4"><?php echo e(trans('global.reset_password')); ?></p>

                    <?php if(session('status')): ?>
                        <div class="alert alert-success text-center" role="alert">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('password.email')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="form-group mb-4">
                            <input id="email" type="email"
                                class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" name="email" required
                                autocomplete="email" autofocus placeholder="<?php echo e(trans('global.login_email')); ?>"
                                value="<?php echo e(old('email')); ?>">

                            <?php if($errors->has('email')): ?>
                                <div class="invalid-feedback">
                                    <?php echo e($errors->first('email')); ?>

                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-success btn-lg">
                                <?php echo e(trans('global.send_password')); ?>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tisoporte\resources\views/auth/passwords/email.blade.php ENDPATH**/ ?>