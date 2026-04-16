<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\EmbeddedVideo;
use App\Models\Video;


class SearchController extends Controller
{

    public function search(Request $request)
    {
        if ($request->input('query')) 
        {
            $q = $request->input('query');

            $videos = Video::query()
                ->where('title', 'LIKE', "%{$q}%")
                ->orWhere('description', 'LIKE', "%{$q}%")
                ->get();

            $embeddedVideos = EmbeddedVideo::query()
                ->where('status', 'published')
                ->where(function ($query) use ($q): void {
                    $query->where('title', 'LIKE', "%{$q}%")
                        ->orWhere('description', 'LIKE', "%{$q}%")
                        ->orWhereJsonContains('tags', $q);
                })
                ->get();
        } 
        else 
        {
            $videos = [];
            $embeddedVideos = [];
        }

        return view('search', compact('videos', 'embeddedVideos'));
    }

}
