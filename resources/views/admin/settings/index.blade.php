@extends('admin.layouts.app', ['pageTitle' => 'Site Settings'])

@section('content')
<div class="space-y-5">
    <x-admin.section-header title="Site Settings" subtitle="Control branding, SEO, homepage sections, and platform behavior." />

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid gap-4 xl:grid-cols-2">
            <x-admin.panel title="Brand & SEO">
                <div class="space-y-4">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-admin-muted">Site Title</label>
                        <input name="site_title" value="{{ old('site_title', $settings['site_title'] ?? config('app.name')) }}" class="w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-admin-muted">SEO Description</label>
                        <textarea name="seo_description" rows="4" class="w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">{{ old('seo_description', $settings['seo_description'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-admin-muted">Footer Links</label>
                        <textarea name="footer_links" rows="3" class="w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">{{ old('footer_links', $settings['footer_links'] ?? '') }}</textarea>
                    </div>
                </div>
            </x-admin.panel>

            <x-admin.panel title="Experience Toggles">
                <div class="space-y-3">
                    @php
                        $toggleFields = [
                            'maintenance_mode' => 'Maintenance Mode',
                            'homepage_show_trending' => 'Show Trending Section',
                            'homepage_show_latest' => 'Show Latest Section',
                            'homepage_show_recommended' => 'Show Recommended Section',
                        ];
                    @endphp

                    @foreach($toggleFields as $field => $label)
                        <label class="flex items-center justify-between rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5">
                            <span class="text-sm text-white">{{ $label }}</span>
                            <input type="hidden" name="{{ $field }}" value="0">
                            <input type="checkbox" name="{{ $field }}" value="1" class="h-4 w-4 rounded border-admin-border bg-admin-base text-admin-accent" @checked(old($field, $settings[$field] ?? '0') == '1')>
                        </label>
                    @endforeach

                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-admin-muted">Legal Pages</label>
                        <textarea name="legal_pages" rows="4" class="w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5 text-sm text-white">{{ old('legal_pages', $settings['legal_pages'] ?? '') }}</textarea>
                    </div>
                </div>
            </x-admin.panel>
        </div>

        <div class="flex justify-end">
            <button class="rounded-xl bg-admin-accent px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-500">Save Settings</button>
        </div>
    </form>
</div>
@endsection
