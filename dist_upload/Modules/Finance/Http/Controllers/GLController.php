<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class GLController extends Controller
{
    public function index()
    {
        return view('finance::gl.index');
    }

    public function create()
    {
        return view('finance::gl.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('finance.gl.index');
    }

    public function show($id)
    {
        return view('finance::gl.show');
    }

    public function edit($id)
    {
        return view('finance::gl.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('finance.gl.index');
    }

    public function destroy($id)
    {
        return redirect()->route('finance.gl.index');
    }
}
