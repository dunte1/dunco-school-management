<?php

namespace Modules\Document\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Document\Models\DocumentShare;

class SharingController extends Controller
{
    public function index(Request $request)
    {
        $query = DocumentShare::with(['document', 'sharedBy', 'sharedWith']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by permission
        if ($request->has('permission') && $request->permission) {
            $query->where('permission', $request->permission);
        }

        // Search by document name or user
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('document', function($docQuery) use ($search) {
                    $docQuery->where('name', 'like', "%{$search}%");
                })->orWhereHas('sharedWith', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        $shares = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('document::sharing.index', compact('shares'));
    }

    public function create()
    {
        $documents = \Modules\Document\Models\Document::all();
        $users = \App\Models\User::all();
        return view('document::sharing.create', compact('documents', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_id' => 'required|exists:documents,id',
            'shared_with_id' => 'required|exists:users,id',
            'permission' => 'required|in:view,edit,admin',
            'expires_at' => 'nullable|date|after:today',
            'message' => 'nullable|string'
        ]);

        try {
            $data = $request->all();
            $data['shared_by_id'] = auth()->id();
            $data['status'] = 'active';

            DocumentShare::create($data);

            return redirect()->route('document.sharing.index')
                           ->with('success', 'Document shared successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to share document: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $share = DocumentShare::with(['document', 'sharedBy', 'sharedWith'])->findOrFail($id);
        return view('document::sharing.show', compact('share'));
    }

    public function edit($id)
    {
        $share = DocumentShare::findOrFail($id);
        $documents = \Modules\Document\Models\Document::all();
        $users = \App\Models\User::all();
        return view('document::sharing.edit', compact('share', 'documents', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'document_id' => 'required|exists:documents,id',
            'shared_with_id' => 'required|exists:users,id',
            'permission' => 'required|in:view,edit,admin',
            'expires_at' => 'nullable|date|after:today',
            'message' => 'nullable|string',
            'status' => 'required|in:active,inactive,expired'
        ]);

        try {
            $share = DocumentShare::findOrFail($id);
            $share->update($request->all());

            return redirect()->route('document.sharing.index')
                           ->with('success', 'Share updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update share: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $share = DocumentShare::findOrFail($id);
            $share->delete();

            return redirect()->route('document.sharing.index')
                           ->with('success', 'Share removed successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to remove share: ' . $e->getMessage()]);
        }
    }
}
