<?php $__env->startSection('title', 'Exam Schedules'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .schedule-premium-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.06), 0 4px 16px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.8);
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
    }
    
    .schedule-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .schedule-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0;
    }
    
    .schedule-btn {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .schedule-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4);
        color: white;
        text-decoration: none;
    }
    
    .schedule-table {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
    }
    
    .schedule-table th {
        background: #f8fafc;
        color: #374151;
        font-weight: 600;
        padding: 1rem;
        border-bottom: 2px solid #e5e7eb;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .schedule-table td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    
    .schedule-table tbody tr:hover {
        background: #f9fafb;
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .status-upcoming {
        background: #fef3c7;
        color: #d97706;
    }
    
    .status-ongoing {
        background: #d1fae5;
        color: #059669;
    }
    
    .status-completed {
        background: #dbeafe;
        color: #2563eb;
    }
    
    .action-btn {
        padding: 0.5rem;
        border-radius: 8px;
        border: none;
        margin-right: 0.25rem;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
    }
    
    .action-btn.view {
        background: #dbeafe;
        color: #2563eb;
    }
    
    .action-btn.edit {
        background: #fef3c7;
        color: #d97706;
    }
    
    .action-btn.delete {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .action-btn:hover {
        transform: scale(1.1);
    }
</style>

<div class="container py-4">
    <div class="schedule-premium-card">
        <div class="schedule-header">
            <h1 class="schedule-title">
                <i class="fas fa-calendar-alt me-3"></i>
                Exam Schedules
            </h1>
            <a href="<?php echo e(route('examination.schedules.create')); ?>" class="schedule-btn">
                <i class="fas fa-plus"></i>
                Create Schedule
            </a>
        </div>
        
        <div class="schedule-table">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Exam Name</th>
                        <th>Date & Time</th>
                        <th>Duration</th>
                        <th>Venue</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div>
                                <strong>Mathematics Final Exam</strong>
                                <br>
                                <small class="text-muted">MATH-101</small>
                            </div>
                        </td>
                        <td>
                            <div>Dec 15, 2024</div>
                            <small class="text-muted">09:00 AM</small>
                        </td>
                        <td>3 hours</td>
                        <td>Room 201</td>
                        <td><span class="status-badge status-upcoming">Upcoming</span></td>
                        <td>
                            <a href="#" class="action-btn view" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="#" class="action-btn edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="action-btn delete" title="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div>
                                <strong>Physics Midterm</strong>
                                <br>
                                <small class="text-muted">PHYS-201</small>
                            </div>
                        </td>
                        <td>
                            <div>Dec 18, 2024</div>
                            <small class="text-muted">02:00 PM</small>
                        </td>
                        <td>2 hours</td>
                        <td>Lab 305</td>
                        <td><span class="status-badge status-upcoming">Upcoming</span></td>
                        <td>
                            <a href="#" class="action-btn view" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="#" class="action-btn edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="action-btn delete" title="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div>
                                <strong>English Literature</strong>
                                <br>
                                <small class="text-muted">ENG-101</small>
                            </div>
                        </td>
                        <td>
                            <div>Dec 20, 2024</div>
                            <small class="text-muted">10:00 AM</small>
                        </td>
                        <td>2.5 hours</td>
                        <td>Auditorium</td>
                        <td><span class="status-badge status-upcoming">Upcoming</span></td>
                        <td>
                            <a href="#" class="action-btn view" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="#" class="action-btn edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="action-btn delete" title="Delete">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('examination::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Examination/resources/views/schedules/index.blade.php ENDPATH**/ ?>