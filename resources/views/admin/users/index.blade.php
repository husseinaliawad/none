@extends('admin.layouts.app', ['pageTitle' => 'User Management'])

@section('content')
<div class="space-y-5">
    <x-admin.section-header title="User Management" subtitle="Review users, roles, and creator activity." />

    <x-admin.panel>
        <form method="GET" class="grid gap-3 md:grid-cols-4">
            <input name="search" value="{{ request('search') }}" placeholder="Search name or email" class="rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2 text-sm text-white md:col-span-2">
            <select name="role" class="rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2 text-sm text-white">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}" @selected(request('role') === $role)>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
            <button class="rounded-xl bg-white/10 px-3 py-2 text-sm font-semibold text-white hover:bg-white/20">Apply Filters</button>
        </form>
    </x-admin.panel>

    <x-admin.panel>
        @if($users->count())
            <div class="overflow-hidden rounded-xl border border-admin-border">
                <table class="min-w-full divide-y divide-admin-border text-sm">
                    <thead class="bg-admin-cardSoft text-left text-xs uppercase tracking-wide text-admin-muted">
                        <tr>
                            <th class="px-4 py-3">User</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Joined</th>
                            <th class="px-4 py-3">Uploads</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-admin-border/70">
                        @foreach($users as $user)
                            @php $videoCount = $user->channel ? $user->channel->videos()->count() : 0; @endphp
                            <tr class="hover:bg-white/5">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-lg bg-gradient-to-br from-admin-accent/80 to-cyan-400/80"></div>
                                        <div>
                                            <p class="font-semibold text-white">{{ $user->name }}</p>
                                            <p class="text-xs text-admin-muted">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3"><x-admin.badge :value="$user->role ?: 'user'">{{ $user->role ?: 'user' }}</x-admin.badge></td>
                                <td class="px-4 py-3"><x-admin.badge :value="$user->email_verified_at ? 'active' : 'pending'">{{ $user->email_verified_at ? 'Verified' : 'Unverified' }}</x-admin.badge></td>
                                <td class="px-4 py-3 text-admin-muted">{{ $user->created_at->diffForHumans() }}</td>
                                <td class="px-4 py-3 text-white">{{ number_format($videoCount) }}</td>
                                <td class="px-4 py-3 text-right"><a href="{{ route('admin.users.show', $user) }}" class="rounded-lg border border-admin-border px-3 py-1.5 text-xs text-white hover:bg-white/10">Profile</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $users->links() }}</div>
        @else
            <x-admin.empty-state title="No users found" description="Try a different filter or wait for new signups." />
        @endif
    </x-admin.panel>
</div>
@endsection
