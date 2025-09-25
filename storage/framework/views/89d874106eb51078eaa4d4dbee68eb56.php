

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h2 class="mb-4">Timetable Dashboard</h2>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="fs-1 text-primary"><i class="fas fa-calendar-alt"></i></div>
                    <h4 class="card-title">Schedules</h4>
                    <p class="card-text fs-3 fw-bold"><?php echo e($totalSchedules); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="fs-1 text-success"><i class="fas fa-door-open"></i></div>
                    <h4 class="card-title">Rooms</h4>
                    <p class="card-text fs-3 fw-bold"><?php echo e($totalRooms); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="fs-1 text-info"><i class="fas fa-th-large"></i></div>
                    <h4 class="card-title">Room Allocations</h4>
                    <p class="card-text fs-3 fw-bold"><?php echo e($totalAllocations); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <div class="fs-1 text-warning"><i class="fas fa-user-clock"></i></div>
                    <h4 class="card-title">Teachers</h4>
                    <p class="card-text fs-3 fw-bold"><?php echo e($totalTeachers); ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body d-flex flex-wrap gap-3 justify-content-center">
                    <a href="<?php echo e(route('class_schedules.index')); ?>" class="btn btn-outline-primary btn-lg"><i class="fas fa-calendar-alt me-2"></i>Class Schedules</a>
                    <a href="<?php echo e(route('teacher_availabilities.index')); ?>" class="btn btn-outline-info btn-lg"><i class="fas fa-user-clock me-2"></i>Teacher Availabilities</a>
                    <a href="<?php echo e(route('rooms.index')); ?>" class="btn btn-outline-success btn-lg"><i class="fas fa-door-open me-2"></i>Rooms</a>
                    <a href="<?php echo e(route('room_allocations.index')); ?>" class="btn btn-outline-dark btn-lg"><i class="fas fa-th-large me-2"></i>Room Allocations</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-12">
            <form class="card shadow-sm p-3 mb-3" method="GET" action="">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label for="timetable_id" class="form-label">Timetable</label>
                        <select name="timetable_id" id="timetable_id" class="form-select">
                            <option value="">All</option>
                            <?php $__currentLoopData = $allTimetables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($t->id); ?>"><?php echo e($t->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="teacher_id" class="form-label">Teacher</label>
                        <select name="teacher_id" id="teacher_id" class="form-select">
                            <option value="">All</option>
                            <?php $__currentLoopData = $allTeachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($t->id); ?>"><?php echo e($t->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="class_id" class="form-label">Class</label>
                        <select name="class_id" id="class_id" class="form-select">
                            <option value="">All</option>
                            <?php $__currentLoopData = $allClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="room_id" class="form-label">Room</label>
                        <select name="room_id" id="room_id" class="form-select">
                            <option value="">All</option>
                            <?php $__currentLoopData = $allRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($r->id); ?>"><?php echo e($r->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 d-flex gap-2">
                        <button type="submit" formaction="<?php echo e(route('class_schedules.export.pdf')); ?>" class="btn btn-outline-secondary btn-lg"><i class="fas fa-file-pdf me-2"></i>Filter & Export PDF</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-end gap-2">
            <button class="btn btn-outline-primary btn-lg mb-3" data-bs-toggle="modal" data-bs-target="#autoGenerateModal">
                <i class="fas fa-magic me-2"></i>Auto-Generate Timetable
            </button>
            <button class="btn btn-outline-secondary btn-lg mb-3" data-bs-toggle="modal" data-bs-target="#printTimetableModal">
                <i class="fas fa-print me-2"></i>Print Timetable
            </button>
            <a href="<?php echo e(route('timetables.reports')); ?>" class="btn btn-outline-info btn-lg mb-3">
                <i class="fas fa-chart-bar me-2"></i>Reports
            </a>
            <?php if(auth()->user() && auth()->user()->hasRole('admin')): ?>
            <a href="<?php echo e(route('audit_logs.index')); ?>" class="btn btn-outline-dark btn-lg mb-3">
                <i class="fas fa-clipboard-list me-2"></i>Audit Logs
            </a>
            <?php endif; ?>
            <?php if(auth()->user() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('timetable_manager'))): ?>
            <a href="<?php echo e(route('timetable.analytics')); ?>" class="btn btn-outline-primary btn-lg mb-3">
                <i class="fas fa-chart-line me-2"></i>Analytics
            </a>
            <?php endif; ?>
            <?php if(auth()->user() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('timetable_manager'))): ?>
            <a href="<?php echo e(route('timetable.conflicts')); ?>" class="btn btn-outline-danger btn-lg mb-3">
                <i class="fas fa-exclamation-triangle me-2"></i>Conflicts
            </a>
            <?php endif; ?>
        </div>
    </div>
    <!-- Auto-Generate Modal -->
    <div class="modal fade" id="autoGenerateModal" tabindex="-1" aria-labelledby="autoGenerateModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form method="POST" action="<?php echo e(route('timetables.autogenerate')); ?>">
            <?php echo csrf_field(); ?>
            <div class="modal-header">
              <h5 class="modal-title" id="autoGenerateModalLabel">Auto-Generate Timetable</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="timetable_id" class="form-label">Target Timetable</label>
                <select name="timetable_id" id="timetable_id" class="form-select" required>
                  <option value="">Select Timetable</option>
                  <?php $__currentLoopData = $allTimetables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($t->id); ?>"><?php echo e($t->name); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Constraints</label>
                <div class="row g-2">
                  <div class="col-md-4">
                    <label>Classes</label>
                    <select name="class_ids[]" class="form-select" multiple>
                      <?php $__currentLoopData = $allClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label>Teachers</label>
                    <select name="teacher_ids[]" class="form-select" multiple>
                      <?php $__currentLoopData = $allTeachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t->id); ?>"><?php echo e($t->name); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label>Rooms</label>
                    <select name="room_ids[]" class="form-select" multiple>
                      <?php $__currentLoopData = $allRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($r->id); ?>"><?php echo e($r->name); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label>Days of Week</label>
                <select name="days[]" class="form-select" multiple>
                  <option value="Monday">Monday</option>
                  <option value="Tuesday">Tuesday</option>
                  <option value="Wednesday">Wednesday</option>
                  <option value="Thursday">Thursday</option>
                  <option value="Friday">Friday</option>
                  <option value="Saturday">Saturday</option>
                  <option value="Sunday">Sunday</option>
                </select>
              </div>
              <div class="mb-3">
                <label>Time Slots (e.g. 08:00-09:00)</label>
                <input type="text" name="time_slots" class="form-control" placeholder="Comma-separated, e.g. 08:00-09:00,09:00-10:00">
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="avoid_double_booking" id="avoid_double_booking" checked>
                <label class="form-check-label" for="avoid_double_booking">Avoid double-booking (teachers/rooms)</label>
              </div>
              <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" name="enforce_availability" id="enforce_availability" checked>
                <label class="form-check-label" for="enforce_availability">Enforce teacher/room availability</label>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Auto-Generate</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Print Timetable Modal -->
    <div class="modal fade" id="printTimetableModal" tabindex="-1" aria-labelledby="printTimetableModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="GET" action="<?php echo e(route('timetables.print')); ?>" target="_blank">
            <div class="modal-header">
              <h5 class="modal-title" id="printTimetableModalLabel">Print Timetable</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="timetable_id_print" class="form-label">Timetable</label>
                <select name="timetable_id" id="timetable_id_print" class="form-select">
                  <option value="">All</option>
                  <?php $__currentLoopData = $allTimetables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($t->id); ?>"><?php echo e($t->name); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="class_id_print" class="form-label">Class</label>
                <select name="class_id" id="class_id_print" class="form-select">
                  <option value="">All</option>
                  <?php $__currentLoopData = $allClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="teacher_id_print" class="form-label">Teacher</label>
                <select name="teacher_id" id="teacher_id_print" class="form-select">
                  <option value="">All</option>
                  <?php $__currentLoopData = $allTeachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($t->id); ?>"><?php echo e($t->name); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="room_id_print" class="form-label">Room</label>
                <select name="room_id" id="room_id_print" class="form-select">
                  <option value="">All</option>
                  <?php $__currentLoopData = $allRooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($r->id); ?>"><?php echo e($r->name); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Print</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Timetable/resources/views/dashboard.blade.php ENDPATH**/ ?>