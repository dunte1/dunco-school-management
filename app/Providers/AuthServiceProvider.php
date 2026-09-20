<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \Modules\Academic\Models\Student::class => \App\Policies\StudentPolicy::class,
        \Modules\Academic\Models\Subject::class => \App\Policies\SubjectPolicy::class,
        \Modules\Finance\Models\Fee::class => \App\Policies\FeePolicy::class,
        \Modules\Finance\Models\Payment::class => \App\Policies\PaymentPolicy::class,
        \Modules\Examination\Models\Exam::class => \App\Policies\ExamPolicy::class,
        \App\Models\Modules\Library\Models\Book::class => \App\Policies\BookPolicy::class,
        \Modules\HR\Models\Staff::class => \App\Policies\StaffPolicy::class,
        \Modules\Hostel\Models\Hostel::class => \App\Policies\HostelPolicy::class,
        \Modules\Transport\Models\Vehicle::class => \App\Policies\VehiclePolicy::class,
        \Modules\Timetable\Models\Timetable::class => \App\Policies\TimetablePolicy::class,
        \Modules\Academic\Models\StudentFee::class => \App\Policies\StudentFeePolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Role::class => \App\Policies\RolePolicy::class,
        \Modules\Communication\Models\Message::class => \App\Policies\MessagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
