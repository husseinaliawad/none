<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphToMany;


class Video extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }


    public function getThumbnailAttribute()
    {

        if ($this->thumbnail_image) {
            return '/videos/' . $this->uid . '/' . $this->thumbnail_image;            
        } else {
            return '/videos/' . 'default.png';
        }
    }


    public function getRouteKeyName()
    {
        return 'uid';
    }


    public function getUploadedDateAttribute()
    {
        $d = new Carbon($this->created_at);

        return $d->toFormattedDateString();
    }


    public function likes()
    {
        return $this->hasMany(Like::class);
    }


    public function dislikes()
    {
        return $this->hasMany(Dislike::class);
    }


    public function doesUserLikedVideo()
    {
        return $this->likes()->where( 'user_id', auth()->id() )->exists();
    }


    public function doesUserDislikeVideo()
    {
        return $this->dislikes()->where( 'user_id', auth()->id() )->exists();
    }


    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('reply_id');
    }


    public function AllCommentsCount()
    {
        return $this->hasMany(Comment::class)->count();
    }

    public function performers(): MorphToMany
    {
        return $this->morphToMany(Performer::class, 'performerable')
            ->withPivot('role_name')
            ->withTimestamps();
    }

    public function tagsCloud(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')
            ->withPivot('score')
            ->withTimestamps();
    }

}
