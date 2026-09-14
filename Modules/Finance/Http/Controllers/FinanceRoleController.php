<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\FinanceRole;

class FinanceRoleController extends Controller
{
    public function index()
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('finance_roles')) {
                $roles = FinanceRole::all();
            } else {
                $roles = collect();
            }
        } catch (\Exception $e) {
            $roles = collect();
        }
        
        return view('finance::roles.index', compact('roles'));
    }

    public function create()
    {
        return view('finance::roles.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('finance.roles.index');
    }

    public function show($id)
    {
        $role = FinanceRole::findOrFail($id);

        return view('finance::roles.show', compact('role'));
    }

    public function edit($id)
    {
        $role = FinanceRole::findOrFail($id);

        return view('finance::roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('finance.roles.index');
    }

    public function destroy($id)
    {
        return redirect()->route('finance.roles.index');
    }
}
