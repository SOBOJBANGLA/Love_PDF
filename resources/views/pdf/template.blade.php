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
    <iframe src="data:application/pdf;base64,{{ base64_encode(file_get_contents($pdfPath)) }}" type="application/pdf"></iframe>
</body>
</html> 