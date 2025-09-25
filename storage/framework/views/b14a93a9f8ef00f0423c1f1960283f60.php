

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h2 class="mb-3">Timetable Calendar</h2>
    <div class="row mb-3 g-2 align-items-center">
        <div class="col-12 col-md-auto">
            <label class="form-label mb-0 me-2">Timetable:</label>
            <select id="filter-timetable" class="form-select form-select-sm">
                <option value="">All Timetables</option>
                <?php $__currentLoopData = $timetables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timetable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($timetable->id); ?>"><?php echo e($timetable->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-12 col-md-auto">
            <label class="form-label mb-0 me-2">Filter:</label>
            <select id="filter-type" class="form-select form-select-sm">
                <option value="">All</option>
                <option value="class">Class</option>
                <option value="teacher">Teacher</option>
                <option value="room">Room</option>
            </select>
        </div>
        <div class="col-12 col-md-auto">
            <input type="text" id="filter-value" class="form-control form-control-sm" placeholder="Enter value...">
        </div>
        <div class="col-12 col-md-auto">
            <button id="apply-filter" class="btn btn-primary btn-sm w-100">Apply</button>
        </div>
    </div>
    <div class="calendar-wrapper" style="overflow-x:auto;">
        <div id="calendar"></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('timetable::components.mobile-navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startPush('scripts'); ?>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        height: 700,
        events: {
            url: '<?php echo e(route('timetable.calendar.data')); ?>',
            method: 'GET',
            failure: function() {
                alert('There was an error while fetching events!');
            }
        },
        eventDidMount: function(info) {
            // Tooltip or details can be added here
        }
    });
    calendar.render();

    document.getElementById('apply-filter').addEventListener('click', function() {
        var type = document.getElementById('filter-type').value;
        var value = document.getElementById('filter-value').value.toLowerCase();
        var timetableId = document.getElementById('filter-timetable').value;
        calendar.removeAllEventSources();
        calendar.addEventSource(function(fetchInfo, successCallback, failureCallback) {
            fetch('<?php echo e(route('timetable.calendar.data')); ?>')
                .then(response => response.json())
                .then(events => {
                    if (timetableId) {
                        events = events.filter(e => e.extendedProps.timetable_id == timetableId);
                    }
                    if (type && value) {
                        events = events.filter(e => (e.extendedProps[type] || '').toLowerCase().includes(value));
                    }
                    successCallback(events);
                })
                .catch(failureCallback);
        });
    });
});
</script>
<?php $__env->stopPush(); ?> 

<?php $__env->startPush('styles'); ?>
<style>
    @media (max-width: 576px) {
        .calendar-wrapper {
            min-width: 350px;
        }
        #calendar {
            min-width: 350px;
        }
    }
</style>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Timetable/resources/views/calendar.blade.php ENDPATH**/ ?>