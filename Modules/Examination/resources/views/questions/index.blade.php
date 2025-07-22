@extends('examination::layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-uppercase text-primary" style="letter-spacing: 2px; font-size: 2rem;">Question Bank</h2>
        <button class="btn btn-primary rounded-pill shadow-lg d-flex align-items-center" style="font-size: 1.1rem;" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
            <i class="fas fa-plus me-2"></i> Add Question
        </button>
    </div>
    <div class="glass-card p-4 mb-4">
        <form class="row g-3 mb-3">
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Search questions..." style="font-size: 1.08rem; padding: 0.75rem 1.2rem; color: #222; background: rgba(255,255,255,0.18);">
            </div>
            <div class="col-md-3">
                <select class="form-select" style="font-size: 1.08rem; padding: 0.75rem 1.2rem;">
                    <option selected>All Categories</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" style="font-size: 1.08rem; padding: 0.75rem 1.2rem;">
                    <option selected>All Statuses</option>
                    <option>Active</option>
                    <option>Inactive</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <button class="btn btn-outline-primary w-100" type="submit" style="font-size: 1.08rem; padding: 0.75rem 1.2rem;">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-premium align-middle mb-0" style="font-size: 1.08rem;">
                <thead>
                    <tr class="text-uppercase">
                        <th scope="col">#</th>
                        <th scope="col"><i class="fas fa-question-circle"></i> Question</th>
                        <th scope="col"><i class="fas fa-list"></i> Category</th>
                        <th scope="col"><i class="fas fa-user"></i> Created By</th>
                        <th scope="col"><i class="fas fa-calendar-alt"></i> Created At</th>
                        <th scope="col" class="text-center"><i class="fas fa-cogs"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1.5px solid rgba(33,150,243,0.13); transition: background 0.2s;">
                        <th scope="row">1</th>
                        <td>What is the capital of France?</td>
                        <td>Geography</td>
                        <td>Admin</td>
                        <td>2024-07-06</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info rounded-pill me-1" style="color: #fff;"><i class="fas fa-eye"></i></button>
                            <button class="btn btn-sm btn-warning rounded-pill me-1" style="color: #fff;"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger rounded-pill" style="color: #fff;"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <button class="btn btn-primary rounded-circle shadow-lg position-fixed" style="bottom: 2rem; right: 2rem; width: 60px; height: 60px; z-index: 1050; font-size: 1.5rem;" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
        <i class="fas fa-plus"></i>
    </button>
    <div class="modal fade" id="addQuestionModal" tabindex="-1" aria-labelledby="addQuestionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content glass-card" style="background: rgba(255,255,255,0.18); border: 1.5px solid #2196f3; color: #0a2342; box-shadow: 0 8px 32px 0 rgba(30,167,255,0.18); padding: 2rem;">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-uppercase text-primary fw-bold" id="addQuestionModalLabel" style="font-size: 1.3rem; letter-spacing: 1px;">Add New Question</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label text-uppercase text-primary fw-bold" style="font-size: 1.08rem;">Question</label>
                            <input type="text" class="form-control" placeholder="Enter question text" style="font-size: 1.08rem; padding: 0.75rem 1.2rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-uppercase text-primary fw-bold" style="font-size: 1.08rem;">Category</label>
                            <select class="form-select" style="font-size: 1.08rem; padding: 0.75rem 1.2rem;">
                                <option selected>Select category</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-uppercase text-primary fw-bold" style="font-size: 1.08rem;">Status</label>
                            <select class="form-select" style="font-size: 1.08rem; padding: 0.75rem 1.2rem;">
                                <option>Active</option>
                                <option>Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-uppercase text-primary fw-bold" style="font-size: 1.08rem;">Options</label>
                            <input type="text" class="form-control mb-2" placeholder="Option 1" style="font-size: 1.08rem; padding: 0.75rem 1.2rem;">
                            <input type="text" class="form-control mb-2" placeholder="Option 2" style="font-size: 1.08rem; padding: 0.75rem 1.2rem;">
                            <input type="text" class="form-control mb-2" placeholder="Option 3" style="font-size: 1.08rem; padding: 0.75rem 1.2rem;">
                            <input type="text" class="form-control" placeholder="Option 4" style="font-size: 1.08rem; padding: 0.75rem 1.2rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-uppercase text-primary fw-bold" style="font-size: 1.08rem;">Correct Answer</label>
                            <input type="text" class="form-control" placeholder="Enter correct answer" style="font-size: 1.08rem; padding: 0.75rem 1.2rem;">
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4" style="font-size: 1.08rem;">Save Question</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 