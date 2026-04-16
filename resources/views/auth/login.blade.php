@extends('layouts.app')

@section('content')
<x-auth.shell title="Login" subtitle="Sign in to manage your channel and content.">
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-400">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus class="w-full rounded-xl border border-white/10 bg-black/30 px-3 py-2.5 text-sm text-white placeholder:text-gray-500 focus:border-red-400 focus:outline-none">
            @error('email') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-400">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="w-full rounded-xl border border-white/10 bg-black/30 px-3 py-2.5 text-sm text-white placeholder:text-gray-500 focus:border-red-400 focus:outline-none">
            @error('password') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-center gap-2 text-sm text-gray-300">
            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} class="h-4 w-4 rounded border-white/20 bg-black/30 text-red-500">
            Remember Me
        </label>

        <button type="submit" class="w-full rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-500">Login</button>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="block text-center text-sm text-red-300 hover:text-red-200">Forgot Your Password?</a>
        @endif
    </form>
</x-auth.shell>
@endsection
