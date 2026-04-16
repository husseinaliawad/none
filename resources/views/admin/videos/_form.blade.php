<div class="grid gap-4 md:grid-cols-2">
    <div class="space-y-4 md:col-span-2">
        <label class="block text-xs font-semibold uppercase tracking-wide text-admin-muted">Title</label>
        <input type="text" name="title" value="{{ old('title', $video->title ?? '') }}" required class="w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white placeholder:text-admin-muted focus:border-admin-accent focus:outline-none">
    </div>

    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide text-admin-muted">Slug</label>
        <input type="text" name="slug" value="{{ old('slug', $video->slug ?? '') }}" class="mt-1 w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">
    </div>

    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide text-admin-muted">Category</label>
        <input type="text" name="category" value="{{ old('category', $video->category ?? '') }}" class="mt-1 w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">
    </div>

    <div class="md:col-span-2">
        <label class="block text-xs font-semibold uppercase tracking-wide text-admin-muted">Description</label>
        <textarea name="description" rows="3" class="mt-1 w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">{{ old('description', $video->description ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide text-admin-muted">Thumbnail URL</label>
        <input type="url" name="thumbnail_url" value="{{ old('thumbnail_url', $video->thumbnail_url ?? '') }}" class="mt-1 w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">
    </div>

    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide text-admin-muted">Source Name</label>
        <input type="text" name="source_name" value="{{ old('source_name', $video->source_name ?? '') }}" class="mt-1 w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">
    </div>

    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide text-admin-muted">Storyboard VTT URL</label>
        <input type="url" name="storyboard_vtt_url" value="{{ old('storyboard_vtt_url', $video->storyboard_vtt_url ?? '') }}" class="mt-1 w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">
    </div>

    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide text-admin-muted">Storyboard Sprite URL</label>
        <input type="url" name="storyboard_sprite_url" value="{{ old('storyboard_sprite_url', $video->storyboard_sprite_url ?? '') }}" class="mt-1 w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">
    </div>

    <div class="md:col-span-2">
        <label class="block text-xs font-semibold uppercase tracking-wide text-admin-muted">Embed URL or iframe code</label>
        <div class="mt-1 flex gap-2">
            <input id="embed_url_input" type="text" name="embed_url" value="{{ old('embed_url', $video->embed_url ?? '') }}" required class="w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">
            <button type="button" id="preview-embed-btn" class="rounded-xl border border-admin-border px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">Preview</button>
        </div>
    </div>

    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide text-admin-muted">Source Video ID</label>
        <input type="text" name="source_video_id" value="{{ old('source_video_id', $video->source_video_id ?? '') }}" class="mt-1 w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">
    </div>

    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide text-admin-muted">Tags (comma separated)</label>
        <input type="text" name="tags" value="{{ old('tags', isset($video) ? implode(',', $video->tags ?? []) : '') }}" class="mt-1 w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">
    </div>

    <div class="md:col-span-2">
        <label class="block text-xs font-semibold uppercase tracking-wide text-admin-muted">Performers</label>
        @php
            $selectedPerformers = old('performer_ids', isset($video) ? $video->performers->pluck('id')->all() : []);
        @endphp
        <select name="performer_ids[]" multiple class="mt-1 min-h-[120px] w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">
            @foreach(($performers ?? collect()) as $performer)
                <option value="{{ $performer->id }}" @selected(in_array($performer->id, $selectedPerformers))>{{ $performer->name }}</option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-admin-muted">Use Ctrl/Cmd click to select multiple performers.</p>
    </div>

    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide text-admin-muted">Status</label>
        <select name="status" class="mt-1 w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white" required>
            <option value="draft" @selected(old('status', $video->status ?? 'draft') === 'draft')>Draft</option>
            <option value="published" @selected(old('status', $video->status ?? '') === 'published')>Published</option>
        </select>
    </div>

    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide text-admin-muted">Published At</label>
        <input type="datetime-local" name="published_at" value="{{ old('published_at', isset($video->published_at) && $video->published_at ? $video->published_at->format('Y-m-d\\TH:i') : '') }}" class="mt-1 w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">
    </div>

    <div class="md:col-span-2">
        <div class="rounded-xl border border-admin-border bg-admin-cardSoft p-3">
            <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-admin-muted">Preview</p>
            <div id="preview-container" class="rounded-lg border border-dashed border-admin-border bg-black/30 p-3 text-sm text-admin-muted">Validate and preview embed URL before saving.</div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('preview-embed-btn');
        const input = document.getElementById('embed_url_input');
        const container = document.getElementById('preview-container');
        if (!button || !input || !container) return;

        button.addEventListener('click', async () => {
            button.disabled = true;
            button.textContent = 'Checking...';
            try {
                const response = await fetch('{{ route('admin.videos.preview') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ embed_url: input.value }),
                });
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Unable to preview this embed.');
                }
                container.innerHTML = '<div class="mb-2 text-xs text-emerald-300">Validated source: ' + data.source_name + '</div><div class="aspect-video overflow-hidden rounded-lg border border-admin-border"><iframe src="' + data.embed_url + '" class="h-full w-full" frameborder="0" allowfullscreen></iframe></div>';
            } catch (error) {
                container.innerHTML = '<div class="rounded-lg bg-rose-500/10 px-3 py-2 text-sm text-rose-200">' + error.message + '</div>';
            } finally {
                button.disabled = false;
                button.textContent = 'Preview';
            }
        });
    });
</script>
@endpush
