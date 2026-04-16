@extends('layouts.app')

@section('content')
<x-auth.shell title="Set New Password" subtitle="Choose a strong password to secure your account.">
    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <label for="email" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-400">Email Address</label>
            <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus class="w-full rounded-xl border border-white/10 bg-black/30 px-3 py-2.5 text-sm text-white focus:border-red-400 focus:outline-none">
            @error('email') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-400">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" class="w-full rounded-xl border border-white/10 bg-black/30 px-3 py-2.5 text-sm text-white focus:border-red-400 focus:outline-none">
            @error('password') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password-confirm" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-400">Confirm Password</label>
            <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password" class="w-full rounded-xl border border-white/10 bg-black/30 px-3 py-2.5 text-sm text-white focus:border-red-400 focus:outline-none">
        </div>

        <button type="submit" class="w-full rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-500">Reset Password</button>
    </form>
</x-auth.shell>
@endsection
