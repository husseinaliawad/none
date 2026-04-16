<div class="grid gap-4 md:grid-cols-2">
    <div class="md:col-span-2">
        <label class="block text-xs font-semibold uppercase tracking-wide text-admin-muted">Name</label>
        <input type="text" name="name" value="{{ old('name', $performer->name ?? '') }}" required class="mt-1 w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">
    </div>
    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide text-admin-muted">Slug</label>
        <input type="text" name="slug" value="{{ old('slug', $performer->slug ?? '') }}" class="mt-1 w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">
    </div>
    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide text-admin-muted">Country</label>
        <input type="text" name="country" value="{{ old('country', $performer->country ?? '') }}" class="mt-1 w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">
    </div>
    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide text-admin-muted">Birth Date</label>
        <input type="date" name="birth_date" value="{{ old('birth_date', isset($performer->birth_date) && $performer->birth_date ? $performer->birth_date->format('Y-m-d') : '') }}" class="mt-1 w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">
    </div>
    <div>
        <label class="block text-xs font-semibold uppercase tracking-wide text-admin-muted">Avatar URL</label>
        <input type="url" name="avatar_url" value="{{ old('avatar_url', $performer->avatar_url ?? '') }}" class="mt-1 w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">
    </div>
    <div class="md:col-span-2">
        <label class="block text-xs font-semibold uppercase tracking-wide text-admin-muted">Bio</label>
        <textarea name="bio" rows="4" class="mt-1 w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">{{ old('bio', $performer->bio ?? '') }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2 text-sm text-white">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $performer->is_active ?? true))>
            Active
        </label>
    </div>
</div>

