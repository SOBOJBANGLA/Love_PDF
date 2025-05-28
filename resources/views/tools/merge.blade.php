@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Merge PDF Files</h4>
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

                    <form action="{{ route('tools.merge.process') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="pdf_files" class="form-label">Select PDF Files</label>
                            <input type="file" class="form-control @error('pdf_files') is-invalid @enderror" 
                                   id="pdf_files" name="pdf_files[]" multiple accept=".pdf" required>
                            @error('pdf_files')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
@endsection 