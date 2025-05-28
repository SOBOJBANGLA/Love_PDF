@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Split PDF Files</h4>
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

                    <form action="{{ route('tools.split.process') }}" method="POST" enctype="multipart/form-data">
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
                            <input type="text" class="form-control @error('page_numbers') is-invalid @enderror" 
                                   id="page_numbers" name="page_numbers" 
                                   placeholder="e.g., 1,3,5-7,9">
                            @error('page_numbers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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

@push('scripts')
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
@endpush
@endsection 