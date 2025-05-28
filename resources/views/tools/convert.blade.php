@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Convert PDF Files</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('tools.convert.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="pdf_file" class="form-label">Select PDF File</label>
                            <input type="file" class="form-control @error('pdf_file') is-invalid @enderror" 
                                   id="pdf_file" name="pdf_file" accept=".pdf" required>
                            @error('pdf_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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

@push('scripts')
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
@endpush
@endsection 