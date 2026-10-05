@extends('layouts.app')

@section('title', 'Supported Media Formats')

@section('content')
<div class="container-xl py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color:#1a237e;">Supported Media Formats</h2>
            <p class="text-muted mb-0">File types supported for questions and exam content</p>
        </div>
        <div>
            <a href="{{ route('examination.questions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create Question
            </a>
            <a href="{{ route('examination.questions.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Questions
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Image Formats -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-image fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">Images</h5>
                            <small>Visual content for questions</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach(['jpg', 'jpeg', 'png', 'gif', 'webp'] as $format)
                        <div class="col-6 mb-2">
                            <span class="badge bg-light text-dark border">{{ strtoupper($format) }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Max size: 10MB per file
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audio Formats -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-success text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-music fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">Audio</h5>
                            <small>Sound files for questions</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach(['mp3', 'wav', 'ogg'] as $format)
                        <div class="col-6 mb-2">
                            <span class="badge bg-light text-dark border">{{ strtoupper($format) }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Max size: 50MB per file
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Formats -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-video fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">Video</h5>
                            <small>Video content for questions</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach(['mp4', 'webm', 'ogg'] as $format)
                        <div class="col-6 mb-2">
                            <span class="badge bg-light text-dark border">{{ strtoupper($format) }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Max size: 100MB per file
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Formats -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-info text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-alt fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">Documents</h5>
                            <small>Document files for questions</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach(['pdf', 'doc', 'docx', 'txt'] as $format)
                        <div class="col-6 mb-2">
                            <span class="badge bg-light text-dark border">{{ strtoupper($format) }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Max size: 25MB per file
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Usage Guidelines -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        Usage Guidelines
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Best Practices</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Use high-quality images for better clarity</li>
                                <li><i class="fas fa-check text-success me-2"></i>Compress large files before uploading</li>
                                <li><i class="fas fa-check text-success me-2"></i>Use descriptive filenames</li>
                                <li><i class="fas fa-check text-success me-2"></i>Test media playback before publishing</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Technical Notes</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-info text-info me-2"></i>Videos are automatically optimized for web</li>
                                <li><i class="fas fa-info text-info me-2"></i>Audio files support multiple quality levels</li>
                                <li><i class="fas fa-info text-info me-2"></i>Documents are converted to web-friendly format</li>
                                <li><i class="fas fa-info text-info me-2"></i>All files are scanned for security</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Instructions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5 class="text-primary mb-3">Ready to Upload Media?</h5>
                    <p class="text-muted mb-4">Create a new question and attach your media files to enhance the learning experience.</p>
                    <a href="{{ route('examination.questions.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>Create Question with Media
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
