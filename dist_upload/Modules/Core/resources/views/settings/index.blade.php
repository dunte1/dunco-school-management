<x-core::layouts.master>
    <div class="container d-flex justify-content-center align-items-start" style="min-height: 80vh;">
        <div class="w-100" style="max-width: 900px;">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm">
                    <li class="breadcrumb-item"><a href="/dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">School Settings</li>
                </ol>
            </nav>
            <h1 class="mb-4"><i class="fas fa-cog"></i> School Settings</h1>
            <div class="row">
                @foreach($settings as $setting)
                <div class="col-md-6 mb-4">
                    <div class="card shadow rounded-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">{{ $setting->display_name ?? $setting->key }}</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">{{ $setting->description ?? 'No description available' }}</p>
                            <div class="mb-3">
                                <strong>Current Value:</strong>
                                <div class="mt-2">
                                    @if($setting->type === 'boolean')
                                        @if($setting->value)
                                            <span class="badge bg-success">Enabled</span>
                                        @else
                                            <span class="badge bg-secondary">Disabled</span>
                                        @endif
                                    @elseif($setting->type === 'json')
                                        <pre class="bg-light p-2 rounded">{{ json_encode(json_decode($setting->value), JSON_PRETTY_PRINT) }}</pre>
                                    @else
                                        <code>{{ $setting->value }}</code>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ route('core.settings.edit', $setting->id) }}" class="btn btn-warning btn-sm" title="Edit" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <style>
    .breadcrumb {
        background: #fff;
        font-size: 1rem;
    }
    .card {
        border-radius: 1.5rem;
    }
    </style>
    <script>
        // Enable Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</x-core::layouts.master> 