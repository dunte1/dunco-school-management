<?php

namespace Modules\Academic\Console\Commands;

use Illuminate\Console\Command;
use Modules\Academic\Services\OnlineClassNotificationService;

class SendOnlineClassReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'online-classes:send-reminders';

    /**
     * The console command description.
     */
    protected $description = 'Send reminder notifications for upcoming online classes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending online class reminders...');
        
        $notificationService = new OnlineClassNotificationService();
        $notificationService->sendReminderNotifications();
        
        $this->info('Online class reminders sent successfully!');
        
        return 0;
    }
}
