<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendBirthdayGreetings extends Command
{
    protected $signature = 'greetings:birthdays';
    protected $description = 'Send birthday greetings to users and students with DOB today';

    public function handle(): int
    {
        $today = now()->format('m-d');
        $total = 0;

        // Users table
        try {
            $users = \App\Models\User::whereNotNull('date_of_birth')->get();
            foreach ($users as $user) {
                if (optional($user->date_of_birth)->format('m-d') === $today) {
                    $this->sendToNotifiable($user->id, $user->name, 'user');
                    $total++;
                }
            }
        } catch (\Throwable $e) { Log::warning('Birthday users query failed: '.$e->getMessage()); }

        // Students (module)
        try {
            if (class_exists('Modules\\Academic\\Models\\Student')) {
                $students = \Modules\Academic\Models\Student::whereNotNull('date_of_birth')->get();
                foreach ($students as $s) {
                    if (optional($s->date_of_birth)->format('m-d') === $today) {
                        $name = trim(($s->first_name ?? '').' '.($s->last_name ?? '')); 
                        $this->sendToNotifiable($s->user_id ?? null, $name, 'student');
                        $total++;
                    }
                }
            }
        } catch (\Throwable $e) { Log::warning('Birthday students query failed: '.$e->getMessage()); }

        $this->info("Birthday greetings sent (queued/displayed) to {$total} recipients.");
        return self::SUCCESS;
    }

    private function sendToNotifiable(?int $userId, string $name, string $type): void
    {
        $title = 'Happy Birthday, '.$name.'! Wishing you a wonderful year ahead!';
        try {
            $template = (string) (\App\Models\Setting::where('key','notifications.birthday.template')->value('value') ?? $title);
            $title = str_replace('{name}', $name, $template);
        } catch (\Throwable $e) {}
        $enabled = true;
        try {
            $enabled = (bool) (\App\Models\Setting::where('key','notifications.birthday.enabled')->value('value') ?? true);
        } catch (\Throwable $e) {}
        if (!$enabled) { return; }
        // In-app notification (Communication module fallback)
        try {
            if (class_exists('Modules\\Communication\\Models\\Notification') && $userId) {
                \Modules\Communication\Models\Notification::create([
                    'type' => 'birthday',
                    'title' => $title,
                    'data' => ['body' => 'Wishing you a wonderful year ahead!'],
                    'notifiable_id' => $userId,
                    'notifiable_type' => \App\Models\User::class,
                ]);
            }
        } catch (\Throwable $e) { Log::warning('Birthday in-app notification failed: '.$e->getMessage()); }

        // Push notification to device tokens
        try {
            if ($userId) {
                $tokens = \App\Models\UserDeviceToken::where('user_id', $userId)->pluck('token');
                foreach ($tokens as $t) {
                    dispatch(new \App\Jobs\SendFcmMessage($t, $title, 'Wishing you a wonderful year ahead!'));
                }
            }
        } catch (\Throwable $e) { Log::warning('Birthday push failed: '.$e->getMessage()); }
    }
}


