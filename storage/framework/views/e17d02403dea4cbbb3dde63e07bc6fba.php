

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Merge PDF Files</h4>
                </div>
                <div class="card-body">
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(session('success')): ?>
                        <div class="alert alert-success">
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('tools.merge.process')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="pdf_files" class="form-label">Select PDF Files</label>
                            <input type="file" class="form-control <?php $__errorArgs = ['pdf_files'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="pdf_files" name="pdf_files[]" multiple accept=".pdf" required>
                            <?php $__errorArgs = ['pdf_files'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">You can select multiple PDF files to merge. Maximum file size: 10MB per file.</div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-object-group me-2"></i>Merge PDFs
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">How to Merge PDF Files</h5>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li>Click the "Choose Files" button to select your PDF files</li>
                        <li>You can select multiple PDF files at once</li>
                        <li>Click the "Merge PDFs" button to combine your files</li>
                        <li>Your merged PDF will be downloaded automatically</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\xampp8.2\htdocs\api_copy\ilove_pdf\resources\views/tools/merge.blade.php ENDPATH**/ ?>