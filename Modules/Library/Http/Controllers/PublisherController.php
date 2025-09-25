<?php

namespace Modules\Library\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function index() {
        $publishers = collect([
            (object)[
                'id' => 1,
                'name' => 'Penguin Random House',
                'email' => 'contact@penguin.com',
                'phone' => '+1234567890',
                'address' => 'New York, NY',
                'created_at' => '2024-01-15'
            ],
            (object)[
                'id' => 2,
                'name' => 'HarperCollins',
                'email' => 'info@harpercollins.com',
                'phone' => '+1234567891',
                'address' => 'London, UK',
                'created_at' => '2024-02-20'
            ],
            (object)[
                'id' => 3,
                'name' => 'Simon & Schuster',
                'email' => 'contact@simonandschuster.com',
                'phone' => '+1234567892',
                'address' => 'New York, NY',
                'created_at' => '2024-03-10'
            ]
        ]);

        return view('library::publishers.index', compact('publishers'));
    }

    public function create() {
        return view('library::publishers.create');
    }

    public function store(Request $request) {
        $request->validate(['name' => 'required|string|max:255']);
        // Storage logic would go here
        return redirect()->route('library.publishers.index')->with('success', 'Publisher created successfully!');
    }

    public function show($id) {
        $publisher = (object)[
            'id' => $id,
            'name' => 'Penguin Random House',
            'email' => 'contact@penguin.com',
            'phone' => '+1234567890',
            'address' => 'New York, NY',
            'created_at' => '2024-01-15'
        ];

        return view('library::publishers.show', compact('publisher'));
    }

    public function edit($id) {
        $publisher = (object)[
            'id' => $id,
            'name' => 'Penguin Random House',
            'email' => 'contact@penguin.com',
            'phone' => '+1234567890',
            'address' => 'New York, NY',
            'created_at' => '2024-01-15'
        ];

        return view('library::publishers.edit', compact('publisher'));
    }

    public function update(Request $request, $id) {
        $request->validate(['name' => 'required|string|max:255']);
        // Update logic would go here
        return redirect()->route('library.publishers.index')->with('success', 'Publisher updated successfully!');
    }

    public function destroy($id) {
        // Delete logic would go here
        return redirect()->route('library.publishers.index')->with('success', 'Publisher deleted successfully!');
    }
} 