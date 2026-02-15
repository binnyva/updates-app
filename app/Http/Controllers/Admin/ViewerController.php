<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Viewer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewerController extends Controller
{
    private function currentUser()
    {
        return Auth::guard('web')->user();
    }

    public function index()
    {
        $viewers = $this->currentUser()->viewers()->orderByPivot('created_at', 'desc')->paginate(20);
        return view('admin.viewers.index', compact('viewers'));
    }

    public function create()
    {
        return view('admin.viewers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'name' => 'nullable|string|max:255',
            'level' => 'required|in:full,limited',
        ]);

        $viewer = Viewer::firstOrCreate(
            ['email' => $validated['email']],
            ['name' => $validated['name'] ?? null]
        );

        $user = $this->currentUser();

        if ($user->viewers()->where('viewer_id', $viewer->id)->exists()) {
            return redirect('/adm/viewers')->with('error', 'This viewer is already added.');
        }

        $user->viewers()->attach($viewer->id, ['level' => $validated['level']]);

        return redirect('/adm/viewers')->with('success', 'Viewer added successfully.');
    }

    public function edit(Viewer $viewer)
    {
        $user = $this->currentUser();
        $pivot = $user->viewers()->where('viewer_id', $viewer->id)->first();

        if (!$pivot) {
            abort(404);
        }

        return view('admin.viewers.edit', [
            'viewer' => $viewer,
            'level' => $pivot->pivot->level,
        ]);
    }

    public function update(Request $request, Viewer $viewer)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'level' => 'required|in:full,limited',
        ]);

        $user = $this->currentUser();

        if (!$user->viewers()->where('viewer_id', $viewer->id)->exists()) {
            abort(404);
        }

        if ($validated['name']) {
            $viewer->update(['name' => $validated['name']]);
        }

        $user->viewers()->updateExistingPivot($viewer->id, ['level' => $validated['level']]);

        return redirect('/adm/viewers')->with('success', 'Viewer updated successfully.');
    }

    public function destroy(Viewer $viewer)
    {
        $this->currentUser()->viewers()->detach($viewer->id);
        return redirect('/adm/viewers')->with('success', 'Viewer removed.');
    }
}
