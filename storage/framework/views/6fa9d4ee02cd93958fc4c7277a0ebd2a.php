<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PDF Template</title>
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        iframe {
            width: 100%;
            height: 100vh;
            border: none;
        }
    </style>
</head>
<body>
    <iframe src="data:application/pdf;base64,<?php echo e(base64_encode(file_get_contents($pdfPath))); ?>" type="application/pdf"></iframe>
</body>
</html> <?php /**PATH F:\xampp8.2\htdocs\api_copy\ilove_pdf\resources\views/pdf/template.blade.php ENDPATH**/ ?>