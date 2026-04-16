<div class="space-y-4">
    @include('includes.recursive', [ 'comments' => $video->comments()->latestFirst()->get() ])
</div>
