@extends('layouts.app')

@section('title', 'Manage Notifications')

@section('content')
<div class="container-fluid">
	<div class="d-flex align-items-center justify-content-between mb-3">
		<h3 class="mb-0 d-flex align-items-center gap-2">
			<i class="fas fa-cogs"></i>
			<span>Manage Notifications</span>
		</h3>
		<a href="{{ route('notification.index') }}" class="btn btn-outline-secondary">
			<i class="fas fa-arrow-left me-1"></i> Back
		</a>
	</div>

	<div class="row g-3">
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header bg-white"><strong>Channels</strong></div>
				<div class="card-body">
					@if(session('success'))
						<div class="alert alert-success">{{ session('success') }}</div>
					@elseif(session('error'))
						<div class="alert alert-danger">{{ session('error') }}</div>
					@endif
					<ul class="list-unstyled mb-0 small">
						<li>Email (SMTP)</li>
						<li>SMS (Gateway)</li>
						<li>In-app</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header bg-white"><strong>Templates</strong></div>
				<div class="card-body">
					<p class="text-muted small mb-2">Define and manage message templates.</p>
					<form method="POST" action="{{ route('notification.templates.test') }}" class="row g-2 align-items-end">
						@csrf
						<div class="col-12">
							<label class="form-label">Test Phone Number</label>
							<input type="text" name="test_phone" class="form-control" placeholder="e.g. +2547XXXXXXXX" required>
						</div>
						<div class="col-12">
							<label class="form-label">Message</label>
							<input type="text" name="test_message" class="form-control" value="Test message from templates" required>
						</div>
						<div class="col-12 d-flex gap-2">
							<button type="submit" class="btn btn-sm btn-primary">
								<i class="fas fa-paper-plane me-1"></i> Test send SMS
							</button>
							<a href="{{ route('settings.global') }}" class="btn btn-sm btn-outline-secondary">Configure Provider</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection


