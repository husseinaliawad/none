<?php

namespace App\Http\Controllers;

use App\Models\ShortClip;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShortsController extends Controller
{
    public function index(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));

        $clips = ShortClip::query()
            ->with(['video', 'embeddedVideo'])
            ->where('status', 'published')
            ->when($query !== '', fn ($builder) => $builder->where('title', 'like', "%{$query}%"))
            ->orderByDesc('highlight_score')
            ->latest()
            ->paginate(24);

        return view('shorts.index', compact('clips', 'query'));
    }
}
