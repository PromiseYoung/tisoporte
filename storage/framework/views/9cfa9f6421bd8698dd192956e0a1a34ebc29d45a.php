<?php $__env->startSection('content'); ?>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card mx-4 shadow-lg borde-0" style="border-radius: 3px;">
                <div class="card-body p-4 d-flex flex-column align-items-center">
                    <div class="text-center mb-4">
                        <img src="<?php echo e(asset('logo/load.png')); ?>" alt="logo" class="img-fluid mb-3" style="max-width: 150px;">
                        <h1 class="h4 text-dark"><?php echo e(trans('panel.site_title')); ?></h1>
                        <p class="text-muted"><?php echo e(trans('global.login')); ?></p>
                    </div>

                    <?php if(session('status')): ?>
                        <div class="alert alert-success mb-4" role="alert">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('login')); ?>" class="w-100">
                        <?php echo csrf_field(); ?>

                        <div class="form-group mb-3">
                            <input id="email" name="email" type="text"
                                class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" required
                                autocomplete="email" autofocus placeholder="<?php echo e(trans('global.login_email')); ?>"
                                value="<?php echo e(old('email', null)); ?>" style="border: 1px solid #dbdbdb; border-radius: 5px;">
                            <?php if($errors->has('email')): ?>
                                <div class="invalid-feedback">
                                    <?php echo e($errors->first('email')); ?>

                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-4">
                            <input id="password" name="password" type="password"
                                class="form-control<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" required
                                placeholder="<?php echo e(trans('global.login_password')); ?>"
                                style="border: 1px solid #dbdbdb; border-radius: 5px;">
                            <?php if($errors->has('password')): ?>
                                <div class="invalid-feedback">
                                    <?php echo e($errors->first('password')); ?>

                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" name="remember" type="checkbox" id="remember" />
                            <label class="form-check-label" for="remember" style="font-size: 14px;">
                                <?php echo e(trans('global.remember_me')); ?>

                            </label>
                        </div>

                        <button type="submit" class="btn btn-success btn-block mb-3" style="border-radius: 5px;">
                            <?php echo e(trans('global.login')); ?>

                        </button>

                        <div class="text-center mb-3">
                            <?php if(Route::has('password.request')): ?>
                                <a class="text-muted" href="<?php echo e(route('password.request')); ?>" style="font-size: 14px;">
                                    <?php echo e(trans('global.forgot_password')); ?>

                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tisoporte\resources\views/auth/login.blade.php ENDPATH**/ ?>