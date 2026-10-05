@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-palette me-2"></i>Display Settings</h3>
        <a href="{{ route('portal.preferences') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Preferences
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-paint-brush me-2"></i>Theme & Appearance</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('portal.profile.update') }}">
                        @csrf
                        <input type="hidden" name="form_type" value="preferences">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Color Theme</h6>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="theme" 
                                           id="theme_light" value="light" {{ $user->getSetting('theme', 'light') === 'light' ? 'checked' : '' }}>
                                    <label class="form-check-label d-flex align-items-center" for="theme_light">
                                        <div class="theme-preview me-3" style="width: 30px; height: 20px; background: #ffffff; border: 1px solid #dee2e6; border-radius: 3px;"></div>
                                        <div>
                                            <strong>Light Theme</strong><br>
                                            <small class="text-muted">Clean and bright interface</small>
                                        </div>
                                    </label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="theme" 
                                           id="theme_dark" value="dark" {{ $user->getSetting('theme') === 'dark' ? 'checked' : '' }}>
                                    <label class="form-check-label d-flex align-items-center" for="theme_dark">
                                        <div class="theme-preview me-3" style="width: 30px; height: 20px; background: #212529; border: 1px solid #495057; border-radius: 3px;"></div>
                                        <div>
                                            <strong>Dark Theme</strong><br>
                                            <small class="text-muted">Easy on the eyes in low light</small>
                                        </div>
                                    </label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="theme" 
                                           id="theme_auto" value="auto" {{ $user->getSetting('theme') === 'auto' ? 'checked' : '' }}>
                                    <label class="form-check-label d-flex align-items-center" for="theme_auto">
                                        <div class="theme-preview me-3" style="width: 30px; height: 20px; background: linear-gradient(45deg, #ffffff 50%, #212529 50%); border: 1px solid #dee2e6; border-radius: 3px;"></div>
                                        <div>
                                            <strong>Auto</strong><br>
                                            <small class="text-muted">Follows your system preference</small>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Accent Color</h6>
                                
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="accent_color" 
                                                   id="accent_blue" value="blue" {{ $user->getSetting('accent_color', 'blue') === 'blue' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="accent_blue">
                                                <div class="color-preview" style="width: 100%; height: 30px; background: #0d6efd; border-radius: 3px; margin-top: 5px;"></div>
                                                <small class="d-block text-center mt-1">Blue</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="accent_color" 
                                                   id="accent_green" value="green" {{ $user->getSetting('accent_color') === 'green' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="accent_green">
                                                <div class="color-preview" style="width: 100%; height: 30px; background: #198754; border-radius: 3px; margin-top: 5px;"></div>
                                                <small class="d-block text-center mt-1">Green</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="accent_color" 
                                                   id="accent_purple" value="purple" {{ $user->getSetting('accent_color') === 'purple' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="accent_purple">
                                                <div class="color-preview" style="width: 100%; height: 30px; background: #6f42c1; border-radius: 3px; margin-top: 5px;"></div>
                                                <small class="d-block text-center mt-1">Purple</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Layout Preferences</h6>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="compact_mode" 
                                           id="compact_mode" value="1" {{ $user->getSetting('compact_mode', false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="compact_mode">
                                        <strong>Compact Mode</strong><br>
                                        <small class="text-muted">Reduce spacing for more content on screen</small>
                                    </label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="sidebar_collapsed" 
                                           id="sidebar_collapsed" value="1" {{ $user->getSetting('sidebar_collapsed', false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sidebar_collapsed">
                                        <strong>Collapsed Sidebar</strong><br>
                                        <small class="text-muted">Start with sidebar collapsed by default</small>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Content Display</h6>
                                
                                <div class="mb-3">
                                    <label for="items_per_page" class="form-label">Items per page</label>
                                    <select class="form-select" id="items_per_page" name="items_per_page">
                                        <option value="10" {{ $user->getSetting('items_per_page', 10) == 10 ? 'selected' : '' }}>10 items</option>
                                        <option value="25" {{ $user->getSetting('items_per_page') == 25 ? 'selected' : '' }}>25 items</option>
                                        <option value="50" {{ $user->getSetting('items_per_page') == 50 ? 'selected' : '' }}>50 items</option>
                                        <option value="100" {{ $user->getSetting('items_per_page') == 100 ? 'selected' : '' }}>100 items</option>
                                    </select>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="show_avatars" 
                                           id="show_avatars" value="1" {{ $user->getSetting('show_avatars', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_avatars">
                                        <strong>Show Profile Pictures</strong><br>
                                        <small class="text-muted">Display user avatars throughout the interface</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Display Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Preview</h5>
                </div>
                <div class="card-body">
                    <div class="preview-container" style="border: 1px solid #dee2e6; border-radius: 5px; padding: 15px; background: #f8f9fa;">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary rounded-circle me-2" style="width: 30px; height: 30px;"></div>
                            <div>
                                <div class="fw-bold">Sample User</div>
                                <small class="text-muted">Student</small>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="bg-light rounded" style="height: 8px; width: 80%; margin-bottom: 5px;"></div>
                            <div class="bg-light rounded" style="height: 8px; width: 60%; margin-bottom: 5px;"></div>
                            <div class="bg-light rounded" style="height: 8px; width: 70%;"></div>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-primary">Action</button>
                            <button class="btn btn-sm btn-outline-secondary">Cancel</button>
                        </div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">This is a preview of how your interface will look with the selected settings.</small>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Display Tips</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb me-2"></i>Theme Selection</h6>
                        <p class="mb-0 small">Choose a theme that's comfortable for your eyes. Dark themes are great for low-light environments.</p>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-mobile-alt me-2"></i>Responsive Design</h6>
                        <p class="mb-0 small">The interface automatically adapts to different screen sizes. Your preferences work across all devices.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
