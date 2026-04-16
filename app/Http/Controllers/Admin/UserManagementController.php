<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->with('channel');

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($inner) use ($search) {
                $inner->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $roles = User::query()->select('role')->distinct()->pluck('role')->filter()->values();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function show(User $user)
    {
        $channel = $user->channel;
        $uploadedVideos = $channel ? $channel->videos()->latest()->take(10)->get() : collect();
        $videoCount = $channel ? $channel->videos()->count() : 0;

        return view('admin.users.show', compact('user', 'channel', 'uploadedVideos', 'videoCount'));
    }
}
