<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BorrowController extends Controller
{
    public function index() {
        return Inertia::render('Library/Borrows/Index');
    }
} 