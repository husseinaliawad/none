<?php

namespace App\Http\Livewire\Video;

use Livewire\Component;

use App\Models\Video;


class WatchVideo extends Component
{
    
    public $video;

    protected $listeners = ['VideoViewed' => 'countView'];


    public function mount(Video $video)
    {
        $this->video = $video;
    }


    public function render()
    {
        $relatedVideos = Video::query()
            ->where('id', '!=', $this->video->id)
            ->where('channel_id', $this->video->channel_id)
            ->latest()
            ->take(16)
            ->get();

        if ($relatedVideos->count() < 12) {
            $fallback = Video::query()
                ->where('id', '!=', $this->video->id)
                ->inRandomOrder()
                ->take(16 - $relatedVideos->count())
                ->get();

            $relatedVideos = $relatedVideos->concat($fallback)->unique('id')->values();
        }

        return view('livewire.video.watch-video', [
            'relatedVideos' => $relatedVideos,
        ])->extends('layouts.app');
    }


    public function countView()
    {

        $this->video->update([
            'views' => $this->video->views + 1,
        ]);
    }

}
