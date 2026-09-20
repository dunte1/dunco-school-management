<?php

namespace Modules\Settings\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Setting;
use App\Models\AuditLog;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::paginate(20);
        return view('settings::index', compact('settings'));
    }

    public function create()
    {
        return view('settings::create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|max:255|unique:settings,key',
            'value' => 'nullable|string',
            'type' => 'required|in:string,integer,boolean,json',
            'description' => 'nullable|string',
        ]);

        Setting::create($request->only(['key', 'value', 'type', 'description']));

        return redirect()->route('settings.index')->with('success', 'Setting created successfully');
    }

    public function show($id)
    {
        $setting = Setting::findOrFail($id);
        return view('settings::show', compact('setting'));
    }

    public function edit($id)
    {
        $setting = Setting::findOrFail($id);
        return view('settings::edit', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        $setting = Setting::findOrFail($id);
        
        $request->validate([
            'key' => 'required|string|max:255|unique:settings,key,' . $id,
            'value' => 'nullable|string',
            'type' => 'required|in:string,integer,boolean,json',
            'description' => 'nullable|string',
        ]);

        $setting->update($request->only(['key', 'value', 'type', 'description']));

        return redirect()->route('settings.index')->with('success', 'Setting updated successfully');
    }

    public function destroy($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->delete();

        return redirect()->route('settings.index')->with('success', 'Setting deleted successfully');
    }

    public function general()
    {
        return view('settings::general');
    }

    public function academic()
    {
        return view('settings::academic');
    }

    public function finance()
    {
        return view('settings::finance');
    }

    public function notifications()
    {
        return view('settings::notifications');
    }

    public function security()
    {
        return view('settings::security');
    }

    public function backup()
    {
        $backups = $this->getBackupList();
        return view('settings::backup', compact('backups'));
    }

    public function global()
    {
        // Get global settings from database (use models so encrypted secrets decrypt)
        $settings = Setting::where('type', 'global')->get()->pluck('value', 'key')->toArray();
        
        // Define available languages
        $languages = [
            'en' => 'English',
            'es' => 'Spanish',
            'fr' => 'French',
            'de' => 'German',
            'it' => 'Italian',
            'pt' => 'Portuguese',
            'ru' => 'Russian',
            'zh' => 'Chinese',
            'ja' => 'Japanese',
            'ko' => 'Korean',
            'ar' => 'Arabic',
            'hi' => 'Hindi',
            'sw' => 'Swahili',
        ];
        
        // Define available timezones
        $timezones = [
            'UTC',
            'America/New_York',
            'America/Chicago',
            'America/Denver',
            'America/Los_Angeles',
            'Europe/London',
            'Europe/Paris',
            'Europe/Berlin',
            'Asia/Tokyo',
            'Asia/Shanghai',
            'Asia/Kolkata',
            'Australia/Sydney',
            'Africa/Nairobi',
            'Africa/Lagos',
            'Africa/Cairo',
        ];
        
        return view('settings::global', compact('settings', 'languages', 'timezones'));
    }

    public function updateGlobal(Request $request)
    {
        // Validate the request
        $request->validate([
            'system_name' => 'required|string|max:255',
            'default_language' => 'required|string|max:10',
            'default_timezone' => 'required|string|max:50',
            'enable_exams' => 'boolean',
            'enable_finance' => 'boolean',
            'enable_attendance' => 'boolean',
            'enable_library' => 'boolean',
            'enable_notifications' => 'boolean',
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer',
            'smtp_user' => 'nullable|string|max:255',
            'smtp_pass' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|string|max:10',
            'smtp_from_address' => 'nullable|email|max:255',
            'sms_gateway_url' => 'nullable|url|max:255',
            'sms_api_key' => 'nullable|string|max:255',
            'sms_sender_id' => 'nullable|string|max:50',
            'api_token' => 'nullable|string|max:255',
            'fcm_server_key' => 'nullable|string|max:255',
            'africastalking_username' => 'nullable|string|max:255',
            'africastalking_api_key' => 'nullable|string|max:255',
        ]);

        // Handle file uploads
        if ($request->hasFile('logo')) {
            $request->validate([
                'logo' => 'required|file|mimes:jpeg,jpg,png,svg,webp|max:2048',
            ]);
            $logoPath = $request->file('logo')->store('logos', 'public');
            $request->merge(['logo_path' => $logoPath]);
        }

        if ($request->hasFile('favicon')) {
            $request->validate([
                'favicon' => 'required|file|mimes:jpeg,jpg,png,svg,webp,ico|max:512',
            ]);
            $faviconPath = $request->file('favicon')->store('favicons', 'public');
            $request->merge(['favicon_path' => $faviconPath]);
        }

        // Save settings to database
        foreach ($request->except(['_token', 'logo', 'favicon', 'delete_logo', 'delete_favicon']) as $key => $value) {
            // A blank secret field means "keep the current value" - never overwrite with empty.
            if (in_array($key, Setting::SECRET_KEYS, true) && ($value === null || $value === '')) {
                continue;
            }

            Setting::updateOrCreate(
                ['key' => $key, 'type' => 'global'],
                ['value' => $value, 'description' => 'Global setting: ' . $key]
            );
        }

        // Handle logo deletion
        if ($request->has('delete_logo')) {
            Setting::where('key', 'logo_path')->delete();
        }

        // Handle favicon deletion
        if ($request->has('delete_favicon')) {
            Setting::where('key', 'favicon_path')->delete();
        }

        return redirect()->route('settings.global')->with('success', 'Global settings updated successfully');
    }

    public function perSchool(Request $request)
    {
        // Get all schools
        $schools = \App\Models\School::all();
        
        // Get selected school ID (default to first school if none selected)
        $schoolId = $request->get('school_id', $schools->first()?->id);
        
        // Get school-specific settings
        $settings = \App\Models\SchoolSetting::where('school_id', $schoolId)
            ->pluck('value', 'key')
            ->toArray();
        
        // Define attendance types
        $attendanceTypes = [
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly',
            'semester' => 'Semester',
        ];
        
        // Define currencies
        $currencies = [
            'USD' => 'US Dollar ($)',
            'EUR' => 'Euro (€)',
            'GBP' => 'British Pound (£)',
            'JPY' => 'Japanese Yen (¥)',
            'CAD' => 'Canadian Dollar (C$)',
            'AUD' => 'Australian Dollar (A$)',
            'CHF' => 'Swiss Franc (CHF)',
            'CNY' => 'Chinese Yuan (¥)',
            'INR' => 'Indian Rupee (₹)',
            'BRL' => 'Brazilian Real (R$)',
            'MXN' => 'Mexican Peso ($)',
            'KRW' => 'South Korean Won (₩)',
            'RUB' => 'Russian Ruble (₽)',
            'ZAR' => 'South African Rand (R)',
            'NGN' => 'Nigerian Naira (₦)',
            'KES' => 'Kenyan Shilling (KSh)',
            'UGX' => 'Ugandan Shilling (USh)',
            'TZS' => 'Tanzanian Shilling (TSh)',
            'GHS' => 'Ghanaian Cedi (GH₵)',
            'ETB' => 'Ethiopian Birr (Br)',
        ];
        
        return view('settings::per_school', compact('settings', 'schools', 'schoolId', 'attendanceTypes', 'currencies'));
    }

    public function updatePerSchool(Request $request)
    {
        // Validate the request
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'grading_system' => 'nullable|string|max:255',
            'attendance_type' => 'nullable|string|max:50',
            'term_start' => 'nullable|date',
            'term_end' => 'nullable|date|after:term_start',
            'default_currency' => 'nullable|string|max:10',
            'fee_structure' => 'nullable|string',
            'school_notice' => 'nullable|string',
        ]);

        $schoolId = $request->school_id;

        // Save school-specific settings
        foreach ($request->except(['_token', 'school_id']) as $key => $value) {
            \App\Models\SchoolSetting::updateOrCreate(
                ['school_id' => $schoolId, 'key' => $key],
                ['value' => $value, 'type' => 'string', 'description' => 'School setting: ' . $key]
            );
        }

        return redirect()->route('settings.per_school', ['school_id' => $schoolId])->with('success', 'School settings updated successfully');
    }

    public function updateGlobalAjax(Request $request)
    {
        foreach ($request->except(['_token']) as $key => $value) {
            if (defined('Setting::SECRET_KEYS') && in_array($key, Setting::SECRET_KEYS, true) && ($value === null || $value === '')) {
                continue;
            }
            Setting::updateOrCreate(
                ['key' => $key, 'type' => 'global'],
                ['value' => $value, 'description' => 'Global setting: ' . $key]
            );
        }
        return response()->json(['success' => true, 'message' => 'Settings updated.']);
    }

    public function updatePerSchoolAjax(Request $request)
    {
        $request->validate(['school_id' => 'required|exists:schools,id']);
        $schoolId = $request->school_id;
        foreach ($request->except(['_token', 'school_id']) as $key => $value) {
            \App\Models\SchoolSetting::updateOrCreate(
                ['school_id' => $schoolId, 'key' => $key],
                ['value' => $value, 'type' => 'string', 'description' => 'School setting: ' . $key]
            );
        }
        return response()->json(['success' => true, 'message' => 'School settings updated.']);
    }

    public function getSettingsAjax(Request $request)
    {
        $type = $request->get('type', 'global');
        if ($type === 'global') {
            $settings = Setting::where('type', 'global')->get()->pluck('value', 'key')->toArray();
        } else {
            $schoolId = $request->get('school_id');
            $settings = $schoolId
                ? \App\Models\SchoolSetting::where('school_id', $schoolId)->pluck('value', 'key')->toArray()
                : [];
        }
        return response()->json(['settings' => $settings]);
    }

    public function backupCreate()
    {
        try {
            $backupDir = storage_path('app/backups');
            if (!File::isDirectory($backupDir)) {
                File::makeDirectory($backupDir, 0755, true);
            }

            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename = "backup_{$timestamp}.sql";
            $dumpPath = $backupDir . DIRECTORY_SEPARATOR . $filename;

            $dbConfig = config('database.connections.' . config('database.default'));
            $host = $dbConfig['host'] ?? '127.0.0.1';
            $port = $dbConfig['port'] ?? 3306;
            $database = $dbConfig['database'] ?? '';
            $username = $dbConfig['username'] ?? '';
            $password = $dbConfig['password'] ?? '';

            $passwordFlag = !empty($password) ? '-p' . escapeshellarg($password) : '';
            $command = sprintf(
                'mysqldump -h %s -P %d -u %s %s %s > %s 2>&1',
                escapeshellarg($host),
                (int)$port,
                escapeshellarg($username),
                $passwordFlag,
                escapeshellarg($database),
                escapeshellarg($dumpPath)
            );

            exec($command, $output, $returnCode);

            if ($returnCode !== 0 || !File::exists($dumpPath)) {
                $fallbackContent = "-- Dunco School Management Database Backup\n";
                $fallbackContent .= "-- Generated: " . now()->toDateTimeString() . "\n";
                $fallbackContent .= "-- Database: {$database}\n\n";

                $tables = DB::select('SHOW TABLES');
                $dbName = DB::getDatabaseName();
                $tableKey = "Tables_in_{$dbName}";

                foreach ($tables as $table) {
                    $tableName = $table->$tableKey ?? null;
                    if (!$tableName) continue;

                    $fallbackContent .= "-- Table: {$tableName}\n";
                    $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                    if (!empty($createTable)) {
                        $fallbackContent .= $createTable[0]->{'Create Table'} ?? '';
                        $fallbackContent .= ";\n\n";
                    }

                    $rows = DB::table($tableName)->get();
                    foreach ($rows as $row) {
                        $values = array_map(function ($v) {
                            return $v === null ? 'NULL' : "'" . addslashes($v) . "'";
                        }, (array) $row);
                        $fallbackContent .= "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $fallbackContent .= "\n";
                }

                File::put($dumpPath, $fallbackContent);
            }

            $size = File::exists($dumpPath) ? File::size($dumpPath) : 0;

            AuditLog::log('settings.backup.create', "Created backup: {$filename}", null, [
                'filename' => $filename,
                'size' => $size,
            ]);

            return redirect()->route('settings.backup')->with('success', "Backup created: {$filename} (" . $this->formatBytes($size) . ")");
        } catch (\Exception $e) {
            \Log::error('Backup creation failed: ' . $e->getMessage());
            return redirect()->route('settings.backup')->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function backupRestore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|string',
        ]);

        $backupFile = $request->input('backup_file');
        $backupDir = storage_path('app/backups');
        $filePath = $backupDir . DIRECTORY_SEPARATOR . basename($backupFile);

        if (!File::exists($filePath)) {
            return redirect()->route('settings.backup')->with('error', 'Backup file not found.');
        }

        try {
            $dbConfig = config('database.connections.' . config('database.default'));
            $host = $dbConfig['host'] ?? '127.0.0.1';
            $port = $dbConfig['port'] ?? 3306;
            $database = $dbConfig['database'] ?? '';
            $username = $dbConfig['username'] ?? '';
            $password = $dbConfig['password'] ?? '';

            $passwordFlag = !empty($password) ? '-p' . escapeshellarg($password) : '';
            $command = sprintf(
                'mysql -h %s -P %d -u %s %s %s < %s 2>&1',
                escapeshellarg($host),
                (int)$port,
                escapeshellarg($username),
                $passwordFlag,
                escapeshellarg($database),
                escapeshellarg($filePath)
            );

            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                $sqlContent = File::get($filePath);
                $statements = array_filter(array_map('trim', explode(';', $sqlContent)));
                foreach ($statements as $statement) {
                    if (!empty($statement) && !str_starts_with($statement, '--')) {
                        DB::unprepared($statement);
                    }
                }
            }

            AuditLog::log('settings.backup.restore', "Restored from backup: {$backupFile}");
            return redirect()->route('settings.backup')->with('success', 'Backup restored successfully.');
        } catch (\Exception $e) {
            \Log::error('Backup restore failed: ' . $e->getMessage());
            return redirect()->route('settings.backup')->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    public function backupDelete(Request $request)
    {
        $request->validate(['backup_file' => 'required|string']);
        $backupDir = storage_path('app/backups');
        $filePath = $backupDir . DIRECTORY_SEPARATOR . basename($request->input('backup_file'));

        if (File::exists($filePath)) {
            File::delete($filePath);
            AuditLog::log('settings.backup.delete', "Deleted backup: " . basename($filePath));
            return back()->with('success', 'Backup deleted.');
        }

        return back()->with('error', 'Backup file not found.');
    }

    private function getBackupList(): array
    {
        $backupDir = storage_path('app/backups');
        if (!File::isDirectory($backupDir)) {
            return [];
        }

        $files = File::files($backupDir);
        $backups = [];
        foreach ($files as $file) {
            if ($file->getExtension() === 'sql') {
                $backups[] = [
                    'filename' => $file->getFilename(),
                    'size' => $file->getSize(),
                    'size_formatted' => $this->formatBytes($file->getSize()),
                    'created_at' => \Carbon\Carbon::createFromTimestamp($file->getMTime()),
                ];
            }
        }

        usort($backups, fn($a, $b) => $b['created_at']->timestamp - $a['created_at']->timestamp);
        return $backups;
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        $size = (float)$bytes;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        return round($size, 2) . ' ' . $units[$i];
    }
}
