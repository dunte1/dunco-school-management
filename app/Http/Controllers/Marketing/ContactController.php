<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\Marketing\ContactSubmitted;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255'],
            'subject' => ['required','string','max:255'],
            'message' => ['required','string','max:5000'],
        ]);

        try {
            Mail::to(config('mail.from.address'))
                ->send(new ContactSubmitted($data));
        } catch (\Throwable $e) {
            Log::error('Contact email failed', ['error' => $e->getMessage()]);
        }

        return back()->with('contact_ok', true);
    }
}


