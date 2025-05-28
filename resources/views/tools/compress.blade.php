@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Compress PDF Files</h4>
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

                    <form action="{{ route('tools.compress.process') }}" method="POST" enctype="multipart/form-data">
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
                            <label class="form-label">Compression Level</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="compression_level" 
                                       id="compression_low" value="low" checked>
                                <label class="form-check-label" for="compression_low">
                                    Low Compression (Better Quality)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="compression_level" 
                                       id="compression_medium" value="medium">
                                <label class="form-check-label" for="compression_medium">
                                    Medium Compression (Balanced)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="compression_level" 
                                       id="compression_high" value="high">
                                <label class="form-check-label" for="compression_high">
                                    High Compression (Smaller Size)
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-compress-alt me-2"></i>Compress PDF
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">How to Compress PDF Files</h5>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li>Click the "Choose File" button to select your PDF file</li>
                        <li>Choose a compression level:
                            <ul>
                                <li><strong>Low Compression:</strong> Best quality, moderate size reduction</li>
                                <li><strong>Medium Compression:</strong> Good balance between quality and size</li>
                                <li><strong>High Compression:</strong> Maximum size reduction, may affect quality</li>
                            </ul>
                        </li>
                        <li>Click the "Compress PDF" button</li>
                        <li>Your compressed PDF will be downloaded automatically</li>
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
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Compressing...';
    });
});
</script>
@endpush
@endsection 