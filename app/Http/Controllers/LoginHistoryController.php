<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginHistoryController extends Controller
{
    /**
     * Display the login history page
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get login-related audit logs for the current user
        $query = AuditLog::where("user_id", $user->id)
            ->where(function ($q) {
                $q->where("action", "like", "%login%")
                    ->orWhere("action", "like", "%logout%")
                    ->orWhere("action", "like", "%auth%");
            })
            ->latest()
            ->paginate(20);

        $loginHistory = $query->through(function ($log) {
            return [
                "id" => $log->id,
                "action" => $log->action,
                "description" => $log->description,
                "ip_address" => $log->ip_address,
                "user_agent" => $this->getDeviceInfo($log->user_agent),
                "location" => $this->getLocationInfo($log->ip_address),
                "created_at" => $log->created_at->format("M j, Y g:i A"),
                "is_successful" => $this->isSuccessfulLogin($log->action),
            ];
        });

        // Default to Blade view for better compatibility
        return view("security.login-history", [
            "loginHistory" => $loginHistory,
            "pagination" => [
                "current_page" => $query->currentPage(),
                "last_page" => $query->lastPage(),
                "per_page" => $query->perPage(),
                "total" => $query->total(),
            ],
        ]);

        // Uncomment below if you prefer Inertia.js rendering
        // return inertia("Auth/LoginHistory/Index", $data);
    }

    /**
     * Get login history data for API
     */
    public function data(Request $request): JsonResponse
    {
        $user = Auth::user();

        $query = AuditLog::where("user_id", $user->id)->where(function ($q) {
            $q->where("action", "like", "%login%")
                ->orWhere("action", "like", "%logout%")
                ->orWhere("action", "like", "%auth%");
        });

        // Apply filters
        if ($request->has("date_from") && $request->date_from) {
            $query->whereDate("created_at", ">=", $request->date_from);
        }

        if ($request->has("date_to") && $request->date_to) {
            $query->whereDate("created_at", "<=", $request->date_to);
        }

        if ($request->has("action") && $request->action) {
            $query->where("action", "like", "%" . $request->action . "%");
        }

        $logs = $query->latest()->paginate($request->get("per_page", 20));

        $loginHistory = $logs->through(function ($log) {
            return [
                "id" => $log->id,
                "action" => $log->action,
                "description" => $log->description,
                "ip_address" => $log->ip_address,
                "user_agent" => $this->getDeviceInfo($log->user_agent),
                "location" => $this->getLocationInfo($log->ip_address),
                "created_at" => $log->created_at->format("M j, Y g:i A"),
                "is_successful" => $this->isSuccessfulLogin($log->action),
            ];
        });

        return response()->json([
            "login_history" => $loginHistory,
            "pagination" => [
                "current_page" => $logs->currentPage(),
                "last_page" => $logs->lastPage(),
                "per_page" => $logs->perPage(),
                "total" => $logs->total(),
            ],
        ]);
    }

    /**
     * Get device information from user agent
     */
    private function getDeviceInfo($userAgent)
    {
        if (!$userAgent) {
            return "Unknown Device";
        }

        // Simple device detection (you can use a library like jenssegers/agent for better detection)
        if (
            strpos($userAgent, "Mobile") !== false ||
            strpos($userAgent, "Android") !== false
        ) {
            return "Mobile Device";
        } elseif (strpos($userAgent, "iPad") !== false) {
            return "iPad";
        } elseif (strpos($userAgent, "iPhone") !== false) {
            return "iPhone";
        } elseif (strpos($userAgent, "Chrome") !== false) {
            return "Chrome Browser";
        } elseif (strpos($userAgent, "Firefox") !== false) {
            return "Firefox Browser";
        } elseif (strpos($userAgent, "Safari") !== false) {
            return "Safari Browser";
        } elseif (strpos($userAgent, "Edge") !== false) {
            return "Edge Browser";
        }

        return "Desktop Browser";
    }

    /**
     * Get location information from IP address
     * Note: This is a basic implementation. For production, consider using a geolocation service
     */
    private function getLocationInfo($ipAddress)
    {
        if (!$ipAddress || $ipAddress === "127.0.0.1" || $ipAddress === "::1") {
            return "Localhost";
        }

        // For demo purposes, return a placeholder
        // In production, you would use a geolocation API like ipapi.co, ipinfo.io, etc.
        return "Location lookup not implemented";
    }

    /**
     * Determine if the login action was successful
     */
    private function isSuccessfulLogin($action)
    {
        $successfulActions = [
            "login",
            "user_login",
            "successful_login",
            "auth_success",
        ];

        foreach ($successfulActions as $successAction) {
            if (strpos(strtolower($action), $successAction) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Export login history to CSV
     */
    public function export(Request $request)
    {
        $user = Auth::user();

        $logs = AuditLog::where("user_id", $user->id)
            ->where(function ($q) {
                $q->where("action", "like", "%login%")
                    ->orWhere("action", "like", "%logout%")
                    ->orWhere("action", "like", "%auth%");
            })
            ->latest()
            ->get();

        $filename =
            "login-history-" .
            $user->id .
            "-" .
            now()->format("Y-m-d") .
            ".csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($logs) {
            $file = fopen("php://output", "w");

            // Header row
            fputcsv($file, [
                "Date",
                "Action",
                "IP Address",
                "Device",
                "Location",
                "Description",
            ]);

            // Data rows
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format("Y-m-d H:i:s"),
                    $log->action,
                    $log->ip_address,
                    $this->getDeviceInfo($log->user_agent),
                    $this->getLocationInfo($log->ip_address),
                    $log->description ?? "",
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
