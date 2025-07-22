<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\LedgerEntry;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        $query = LedgerEntry::query();
        if ($request->filled('account')) {
            $query->where('account', $request->account);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        $entries = $query->orderBy('date', 'desc')->get();
        return view('finance::ledgers.index', compact('entries'));
    }
} 