@extends('layouts.app')

@section('content')
<x-auth.shell title="Reset Password" subtitle="Send a password reset link to your email.">
    @if (session('status'))
        <div class="mb-4 rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-3 py-2 text-sm text-emerald-200">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <div>
            <label for="email" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-400">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus class="w-full rounded-xl border border-white/10 bg-black/30 px-3 py-2.5 text-sm text-white focus:border-red-400 focus:outline-none">
            @error('email') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="w-full rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-500">Send Reset Link</button>
    </form>
</x-auth.shell>
@endsection
