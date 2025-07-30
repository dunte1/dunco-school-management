<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\NavigationHelper;

class DebugController extends Controller
{
    public function permissions()
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Not logged in']);
        }

        $user = auth()->user();
        
        return response()->json([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getAllRoleNames()->toArray(),
                'permissions' => $user->getAllPermissionNames()->toArray(),
            ],
            'modules' => [
                'core' => NavigationHelper::canAccessModule('core'),
                'academic' => NavigationHelper::canAccessModule('academic'),
                'examination' => NavigationHelper::canAccessModule('examination'),
                'finance' => NavigationHelper::canAccessModule('finance'),
                'hr' => NavigationHelper::canAccessModule('hr'),
                'library' => NavigationHelper::canAccessModule('library'),
                'timetable' => NavigationHelper::canAccessModule('timetable'),
                'attendance' => NavigationHelper::canAccessModule('attendance'),
                'communication' => NavigationHelper::canAccessModule('communication'),
                'portal' => NavigationHelper::canAccessModule('portal'),
                'settings' => $user->hasPermission('settings.view'),
            ],
            'available_modules' => NavigationHelper::getUserModules(),
        ]);
    }
} 