

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Split PDF Files</h4>
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

                    <form action="<?php echo e(route('tools.split.process')); ?>" method="POST" enctype="multipart/form-data">
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
                            <label class="form-label">Split Type</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="split_type" id="split_page" 
                                       value="page" checked onchange="togglePageNumbers()">
                                <label class="form-check-label" for="split_page">
                                    Split by Pages (Each page as separate PDF)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="split_type" id="split_range" 
                                       value="range" onchange="togglePageNumbers()">
                                <label class="form-check-label" for="split_range">
                                    Split by Page Ranges
                                </label>
                            </div>
                        </div>

                        <div class="mb-3" id="page_numbers_div" style="display: none;">
                            <label for="page_numbers" class="form-label">Page Numbers/Ranges</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['page_numbers'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="page_numbers" name="page_numbers" 
                                   placeholder="e.g., 1,3,5-7,9">
                            <?php $__errorArgs = ['page_numbers'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">
                                Enter page numbers or ranges separated by commas.<br>
                                Examples: 1,3,5-7,9 (for pages 1, 3, 5 through 7, and 9)
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-cut me-2"></i>Split PDF
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">How to Split PDF Files</h5>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li>Click the "Choose File" button to select your PDF file</li>
                        <li>Choose how you want to split the PDF:
                            <ul>
                                <li><strong>Split by Pages:</strong> Each page will be saved as a separate PDF file</li>
                                <li><strong>Split by Page Ranges:</strong> Specify which pages or ranges of pages you want to extract</li>
                            </ul>
                        </li>
                        <li>Click the "Split PDF" button</li>
                        <li>Your split PDFs will be downloaded as a ZIP file</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function togglePageNumbers() {
    const splitType = document.querySelector('input[name="split_type"]:checked').value;
    const pageNumbersDiv = document.getElementById('page_numbers_div');
    const pageNumbersInput = document.getElementById('page_numbers');
    
    if (splitType === 'range') {
        pageNumbersDiv.style.display = 'block';
        pageNumbersInput.required = true;
    } else {
        pageNumbersDiv.style.display = 'none';
        pageNumbersInput.required = false;
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\xampp8.2\htdocs\api_copy\ilove_pdf\resources\views/tools/split.blade.php ENDPATH**/ ?>