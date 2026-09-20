@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">API Documentation</h1>
        <div class="text-muted">REST API reference and endpoints</div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Base URL</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">/api/v1</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-link fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Auth Method</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">Bearer Token</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shield-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Format</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">JSON</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-code fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rate Limit</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">60 req/min</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tachometer-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Available Endpoints</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Method</th>
                                    <th>Endpoint</th>
                                    <th>Description</th>
                                    <th>Auth Required</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-success">GET</span></td>
                                    <td><code>/api/v1/user</code></td>
                                    <td>Get authenticated user details</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">GET</span></td>
                                    <td><code>/api/v1/sidebar/data</code></td>
                                    <td>Get sidebar navigation data</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">GET</span></td>
                                    <td><code>/api/v1/sidebar/user-permissions</code></td>
                                    <td>Get user permissions for sidebar</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">POST</span></td>
                                    <td><code>/api/v1/sidebar/check-permission</code></td>
                                    <td>Check if user has a specific permission</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">GET</span></td>
                                    <td><code>/api/v1/attendance/analytics/student</code></td>
                                    <td>Get student attendance analytics</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">GET</span></td>
                                    <td><code>/api/v1/attendance/analytics/staff</code></td>
                                    <td>Get staff attendance analytics</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">GET</span></td>
                                    <td><code>/api/v1/attendance/past-records</code></td>
                                    <td>Get past attendance records</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-warning">PUT</span></td>
                                    <td><code>/api/v1/attendance/past-records/{id}</code></td>
                                    <td>Update a past attendance record</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">POST</span></td>
                                    <td><code>/api/v1/attendance/biometric</code></td>
                                    <td>Submit a biometric attendance event</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">POST</span></td>
                                    <td><code>/api/v1/attendance/qr</code></td>
                                    <td>Submit a QR code attendance event</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">POST</span></td>
                                    <td><code>/api/v1/attendance/face</code></td>
                                    <td>Submit a face recognition attendance event</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">POST</span></td>
                                    <td><code>/api/v1/attendance/acknowledge</code></td>
                                    <td>Parent acknowledgment of attendance</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">GET</span></td>
                                    <td><code>/api/v1/attendance/biometric-logs</code></td>
                                    <td>Get biometric attendance logs</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">GET</span></td>
                                    <td><code>/api/v1/attendance/qr-logs</code></td>
                                    <td>Get QR code attendance logs</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">GET</span></td>
                                    <td><code>/api/v1/attendance/face-logs</code></td>
                                    <td>Get face recognition attendance logs</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">GET</span></td>
                                    <td><code>/api/v1/attendance/acknowledgment-logs</code></td>
                                    <td>Get parent acknowledgment logs</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">POST</span></td>
                                    <td><code>/api/v1/attendance/notifications/bulk</code></td>
                                    <td>Send bulk attendance notifications</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">POST</span></td>
                                    <td><code>/api/v1/attendance/notifications/x-days-absent</code></td>
                                    <td>Send X-days absent alerts</td>
                                    <td>Yes</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-success">GET</span></td>
                                    <td><code>/search</code></td>
                                    <td>Global search across all modules</td>
                                    <td>No</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Authentication</h6>
                </div>
                <div class="card-body">
                    <p>All API requests require authentication via a Bearer token. Include the token in the <code>Authorization</code> header:</p>
                    <pre class="bg-dark text-light p-3 rounded"><code>Authorization: Bearer {your-api-token}</code></pre>
                    <p class="mb-0">Obtain a token by logging in via the <code>/login</code> endpoint.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Response Format</h6>
                </div>
                <div class="card-body">
                    <p>All responses are returned in JSON format with the following structure:</p>
                    <pre class="bg-dark text-light p-3 rounded"><code>{
    "success": true,
    "message": "Description",
    "data": { ... }
}</code></pre>
                    <p class="mb-0">Error responses include an <code>errors</code> object with validation details.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
