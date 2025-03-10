<?php $__env->startSection('content'); ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><?php echo e(trans('global.create')); ?> <?php echo e(trans('cruds.ticket.title_singular')); ?></h5>
        </div>

        <div class="card-body">
            <form action="<?php echo e(route('admin.tickets.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <div class="form-group <?php echo e($errors->has('title') ? 'has-error' : ''); ?>">
                    <label for="title"><?php echo e(trans('cruds.ticket.fields.title')); ?>*</label>
                    <input type="text" id="title" name="title" class="form-control form-control-lg"
                        value="<?php echo e(old('title', isset($ticket) ? $ticket->title : '')); ?>" required>
                    <?php if($errors->has('title')): ?>
                        <div class="invalid-feedback d-block">
                            <?php echo e($errors->first('title')); ?>

                        </div>
                    <?php endif; ?>
                    <small class="form-text text-muted">
                        <?php echo e(trans('cruds.ticket.fields.title_helper')); ?>

                    </small>
                </div>

                <div class="form-group <?php echo e($errors->has('content') ? 'has-error' : ''); ?>">
                    <label for="content"><?php echo e(trans('cruds.ticket.fields.content')); ?></label>
                    <textarea id="content" name="content" class="form-control form-control-lg" required><?php echo e(old('content', isset($ticket) ? $ticket->content : '')); ?></textarea>
                    <?php if($errors->has('content')): ?>
                        <div class="invalid-feedback d-block">
                            <?php echo e($errors->first('content')); ?>

                        </div>
                    <?php endif; ?>
                    <small class="form-text text-muted">
                        <?php echo e(trans('cruds.ticket.fields.content_helper')); ?>

                    </small>
                </div>

                <div class="form-group <?php echo e($errors->has('attachments') ? 'has-error' : ''); ?>">
                    <label for="attachments"><?php echo e(trans('cruds.ticket.fields.attachments')); ?></label>
                    <div class="needsclick dropzone" id="attachments-dropzone"></div>
                    <?php if($errors->has('attachments')): ?>
                        <div class="invalid-feedback d-block">
                            <?php echo e($errors->first('attachments')); ?>

                        </div>
                    <?php endif; ?>
                    <small class="form-text text-muted">
                        <?php echo e(trans('cruds.ticket.fields.attachments_helper')); ?>

                    </small>
                </div>

                <div class="form-group <?php echo e($errors->has('status_id') ? 'has-error' : ''); ?>">
                    <label for="status"><?php echo e(trans('cruds.ticket.fields.status')); ?>*</label>
                    <select name="status_id" id="status" class="form-control form-control-lg select2" required>
                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($id); ?>"
                                <?php echo e((isset($ticket) && $ticket->status ? $ticket->status->id : old('status_id')) == $id ? 'selected' : ''); ?>>
                                <?php echo e($status); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php if($errors->has('status_id')): ?>
                        <div class="invalid-feedback d-block">
                            <?php echo e($errors->first('status_id')); ?>

                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group <?php echo e($errors->has('priority_id') ? 'has-error' : ''); ?>">
                    <label for="priority"><?php echo e(trans('cruds.ticket.fields.priority')); ?>*</label>
                    <select name="priority_id" id="priority" class="form-control form-control-lg select2" required>
                        <?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $priority): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($id); ?>"
                                <?php echo e((isset($ticket) && $ticket->priority ? $ticket->priority->id : old('priority_id')) == $id ? 'selected' : ''); ?>>
                                <?php echo e($priority); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php if($errors->has('priority_id')): ?>
                        <div class="invalid-feedback d-block">
                            <?php echo e($errors->first('priority_id')); ?>

                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group <?php echo e($errors->has('category_id') ? 'has-error' : ''); ?>">
                    <label for="category"><?php echo e(trans('cruds.ticket.fields.category')); ?>*</label>
                    <select name="category_id" id="category" class="form-control form-control-lg select2" required>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($id); ?>"
                                <?php echo e((isset($ticket) && $ticket->category ? $ticket->category->id : old('category_id')) == $id ? 'selected' : ''); ?>>
                                <?php echo e($category); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php if($errors->has('category_id')): ?>
                        <div class="invalid-feedback d-block">
                            <?php echo e($errors->first('category_id')); ?>

                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="localidad_id">Localidad</label>
                    <select id="localidad_id" name="localidad_id"
                        class="form-control form-control-lg select2 <?php $__errorArgs = ['localidad_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-pill"
                        required>
                        <?php $__currentLoopData = $localidad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $nombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($id); ?>"
                                <?php echo e((old('localidad_id') ?? (isset($ticket) ? $ticket->localidad_id : null)) == $id ? 'selected' : ''); ?>>
                                <?php echo e($nombre); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['localidad_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group <?php echo e($errors->has('author_name') ? 'has-error' : ''); ?>">
                    <label for="author_name"><?php echo e(trans('cruds.ticket.fields.author_name')); ?></label>
                    <input type="text" id="author_name" name="author_name" class="form-control form-control-lg"
                        value="<?php echo e(old('author_name', isset($ticket) ? $ticket->author_name : '')); ?>" required>
                    <?php if($errors->has('author_name')): ?>
                        <div class="invalid-feedback d-block">
                            <?php echo e($errors->first('author_name')); ?>

                        </div>
                    <?php endif; ?>
                    <small class="form-text text-muted">
                        <?php echo e(trans('cruds.ticket.fields.author_name_helper')); ?>

                    </small>
                </div>

                <div class="form-group <?php echo e($errors->has('author_email') ? 'has-error' : ''); ?>">
                    <label for="author_email"><?php echo e(trans('cruds.ticket.fields.author_email')); ?></label>
                    <input type="email" id="author_email" name="author_email" class="form-control form-control-lg"
                        value="<?php echo e(old('author_email', isset($ticket) ? $ticket->author_email : '')); ?>" required>
                    <?php if($errors->has('author_email')): ?>
                        <div class="invalid-feedback d-block">
                            <?php echo e($errors->first('author_email')); ?>

                        </div>
                    <?php endif; ?>
                    <small class="form-text text-muted">
                        <?php echo e(trans('cruds.ticket.fields.author_email_helper')); ?>

                    </small>
                </div>

                <?php if(auth()->user()->isAdmin()): ?>
                    <div class="form-group <?php echo e($errors->has('assigned_to_user_id') ? 'has-error' : ''); ?>">
                        <label for="assigned_to_user"><?php echo e(trans('cruds.ticket.fields.assigned_to_user')); ?></label>
                        <select name="assigned_to_user_id" id="assigned_to_user"
                            class="form-control form-control-lg select2">
                            <?php $__currentLoopData = $assigned_to_users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $assigned_to_user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($id); ?>"
                                    <?php echo e((isset($ticket) && $ticket->assigned_to_user ? $ticket->assigned_to_user->id : old('assigned_to_user_id')) == $id ? 'selected' : ''); ?>>
                                    <?php echo e($assigned_to_user); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php if($errors->has('assigned_to_user_id')): ?>
                            <div class="invalid-feedback d-block">
                                <?php echo e($errors->first('assigned_to_user_id')); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary btn-lg"><?php echo e(trans('global.save')); ?></button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        var uploadedAttachmentsMap = {}
        Dropzone.options.attachmentsDropzone = {
            url: '<?php echo e(route('admin.tickets.storeMedia')); ?>',
            maxFilesize: 2, // MB
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
            },
            params: {
                size: 2
            },
            success: function(file, response) {
                $('form').append('<input type="hidden" name="attachments[]" value="' + response.name + '">')
                uploadedAttachmentsMap[file.name] = response.name
            },
            removedfile: function(file) {
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedAttachmentsMap[file.name]
                }
                $('form').find('input[name="attachments[]"][value="' + name + '"]').remove()
            },
            init: function() {
                <?php if(isset($ticket) && $ticket->attachments): ?>
                    var files =
                        <?php echo json_encode($ticket->attachments); ?>

                    for (var i in files) {
                        var file = files[i]
                        this.options.addedfile.call(this, file)
                        file.previewElement.classList.add('dz-complete')
                        $('form').append('<input type="hidden" name="attachments[]" value="' + file.file_name +
                            '">')
                    }
                <?php endif; ?>
            },
            error: function(file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\tisoporte\resources\views/admin/tickets/create.blade.php ENDPATH**/ ?>