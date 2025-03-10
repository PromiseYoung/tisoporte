

<?php $__env->startSection('content'); ?>
    <div class="container mt-5">
        <div class="alert alert-warning text-center" role="alert">
            <h1 class="display-4">¡Oops! La página ha expirado.</h1>
            <p class="lead">Parece que has perdido la sesión. Por favor, inténtalo de nuevo.</p>
            <a href="<?php echo e(url('/')); ?>" class="btn btn-primary">Regresar a la página de inicio</a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tisoporte\resources\views/errors/419.blade.php ENDPATH**/ ?>