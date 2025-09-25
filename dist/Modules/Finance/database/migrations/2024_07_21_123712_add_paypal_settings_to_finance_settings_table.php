<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Finance\Entities\FinanceSetting;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // This migration adds new keys to the existing 'settings' JSON column
        // in the 'finance_settings' table. No schema change is needed,
        // but this file documents the update.

        if (Schema::hasTable('finance_settings')) {
            $settings = FinanceSetting::first();
            if ($settings) {
                $currentSettings = $settings->settings;
                $newSettings = array_merge($currentSettings, [
                    'paypal_enabled' => false,
                    'paypal_mode' => 'sandbox',
                    'paypal_sandbox_client_id' => '',
                    'paypal_sandbox_client_secret' => '',
                    'paypal_live_client_id' => '',
                    'paypal_live_client_secret' => '',
                ]);
                $settings->settings = $newSettings;
                $settings->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This reverses the addition of the new keys to the 'settings' JSON column.
        if (Schema::hasTable('finance_settings')) {
            $settings = FinanceSetting::first();
            if ($settings) {
                $currentSettings = $settings->settings;
                unset(
                    $currentSettings['paypal_enabled'],
                    $currentSettings['paypal_mode'],
                    $currentSettings['paypal_sandbox_client_id'],
                    $currentSettings['paypal_sandbox_client_secret'],
                    $currentSettings['paypal_live_client_id'],
                    $currentSettings['paypal_live_client_secret']
                );
                $settings->settings = $currentSettings;
                $settings->save();
            }
        }
    }
}; 