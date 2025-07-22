<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Finance\Models\LedgerEntry;

class GLController extends Controller
{
    public function index()
    {
        $entries = LedgerEntry::orderBy('date', 'desc')->get();
        return view('finance::gl.index', compact('entries'));
    }

    public function show(LedgerEntry $entry)
    {
        return view('finance::gl.show', compact('entry'));
    }
} 