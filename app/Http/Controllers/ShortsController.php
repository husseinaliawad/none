<?php

namespace App\Http\Controllers;

use App\Models\ShortClip;
use Illuminate\View\View;

class ShortsController extends Controller
{
    public function index(): View
    {
        $clips = ShortClip::query()
            ->with(['video', 'embeddedVideo'])
            ->where('status', 'published')
            ->orderByDesc('highlight_score')
            ->latest()
            ->paginate(24);

        return view('shorts.index', compact('clips'));
    }
}

