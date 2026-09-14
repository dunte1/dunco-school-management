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
        \Modules\Academic\Models\StudentFee::class => \App\Policies\StudentFeePolicy::class,
        \Modules\Academic\Models\Student::class => \App\Policies\StudentPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Role::class => \App\Policies\RolePolicy::class,
        \Modules\Finance\Models\Payment::class => \App\Policies\PaymentPolicy::class,
        \Modules\Examination\Models\Exam::class => \App\Policies\ExamPolicy::class,
        \Modules\Communication\Models\Message::class => \App\Policies\MessagePolicy::class,
        \App\Models\Modules\Library\Models\Book::class => \App\Policies\BookPolicy::class,
        \Modules\Timetable\Models\Timetable::class => \App\Policies\TimetablePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
