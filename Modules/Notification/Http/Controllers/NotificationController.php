<?php

namespace Modules\Notification\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NotificationController extends Controller
{
    public function index()
    {
        return view('notification::index');
    }

    public function create()
    {
        return view('notification::create');
    }

    public function store(Request $request)
    {
        // Notification creation logic
        return redirect()->route('notification.index');
    }

    public function show($id)
    {
        return view('notification::show');
    }

    public function edit($id)
    {
        return view('notification::edit');
    }

    public function update(Request $request, $id)
    {
        // Notification update logic
        return redirect()->route('notification.index');
    }

    public function destroy($id)
    {
        // Notification delete logic
        return redirect()->route('notification.index');
    }

    public function send($id)
    {
        // Send notification logic
        return redirect()->route('notification.index')->with('success', 'Notification sent successfully');
    }

    public function templates()
    {
        return view('notification::templates');
    }

    public function settings()
    {
        return view('notification::settings');
    }
}
