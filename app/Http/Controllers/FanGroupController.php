<?php

namespace App\Http\Controllers;

use App\Models\FanGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FanGroupController extends Controller
{
    public function index(): View
    {
        $groups = FanGroup::query()->with('performer')->latest()->paginate(18);

        return view('fan-groups.index', compact('groups'));
    }

    public function show(FanGroup $group): View
    {
        $group->load(['performer', 'members']);

        return view('fan-groups.show', compact('group'));
    }

    public function join(FanGroup $group): RedirectResponse
    {
        abort_unless(auth()->check(), 403);

        $group->members()->syncWithoutDetaching([
            auth()->id() => [
                'role' => 'member',
                'joined_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        return back()->with('status', 'Joined group successfully.');
    }
}

