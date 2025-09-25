<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Log;

class QueueServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Monitor queue performance
        $this->configureQueueMonitoring();
    }

    /**
     * Configure queue monitoring and optimization
     */
    private function configureQueueMonitoring(): void
    {
        // Monitor job processing
        Queue::before(function (JobProcessing $event) {
            Log::info('Job started processing', [
                'job' => get_class($event->job->resolveName()),
                'queue' => $event->job->getQueue(),
                'attempts' => $event->job->attempts(),
            ]);
        });

        // Monitor job completion
        Queue::after(function (JobProcessed $event) {
            Log::info('Job completed successfully', [
                'job' => get_class($event->job->resolveName()),
                'queue' => $event->job->getQueue(),
                'time' => $event->job->resolveName() . ' completed',
            ]);
        });

        // Monitor job failures
        Queue::failing(function (JobFailed $event) {
            Log::error('Job failed', [
                'job' => get_class($event->job->resolveName()),
                'queue' => $event->job->getQueue(),
                'exception' => $event->exception->getMessage(),
            ]);
        });
    }
}

