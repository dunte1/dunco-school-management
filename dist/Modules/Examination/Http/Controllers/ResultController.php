<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ResultController extends Controller
{
    public function index()
    {
        return view('examination::results.index');
    }

    public function create()
    {
        return view('examination::results.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('examination.results.index');
    }

    public function show($id)
    {
        return view('examination::results.show');
    }

    public function edit($id)
    {
        return view('examination::results.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('examination.results.index');
    }

    public function destroy($id)
    {
        return redirect()->route('examination.results.index');
    }
}
