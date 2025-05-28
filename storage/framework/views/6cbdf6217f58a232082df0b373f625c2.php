

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Convert PDF Files</h4>
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

                    <form action="<?php echo e(route('tools.convert.process')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="pdf_file" class="form-label">Select PDF File</label>
                            <input type="file" class="form-control <?php $__errorArgs = ['pdf_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="pdf_file" name="pdf_file" accept=".pdf" required>
                            <?php $__errorArgs = ['pdf_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Maximum file size: 10MB</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Convert To</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="output_format" 
                                       id="format_jpg" value="jpg" checked>
                                <label class="form-check-label" for="format_jpg">
                                    JPG Images
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="output_format" 
                                       id="format_png" value="png">
                                <label class="form-check-label" for="format_png">
                                    PNG Images
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="output_format" 
                                       id="format_docx" value="docx">
                                <label class="form-check-label" for="format_docx">
                                    DOCX Document
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-exchange-alt me-2"></i>Convert PDF
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">How to Convert PDF Files</h5>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li>Click the "Choose File" button to select your PDF file</li>
                        <li>Choose the output format:
                            <ul>
                                <li><strong>JPG Images:</strong> Convert each page to a JPG image</li>
                                <li><strong>PNG Images:</strong> Convert each page to a PNG image</li>
                                <li><strong>DOCX Document:</strong> Convert PDF to editable Word document</li>
                            </ul>
                        </li>
                        <li>Click the "Convert PDF" button</li>
                        <li>Your converted files will be downloaded automatically:
                            <ul>
                                <li>For images: A ZIP file containing all pages as separate images</li>
                                <li>For DOCX: A single Word document file</li>
                            </ul>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Converting...';
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\xampp8.2\htdocs\api_copy\ilove_pdf\resources\views/tools/convert.blade.php ENDPATH**/ ?>