@extends('layouts.app')

@section('content')
<x-auth.shell title="Confirm Password" subtitle="Please confirm your password before continuing.">
    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <div>
            <label for="password" class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-400">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="w-full rounded-xl border border-white/10 bg-black/30 px-3 py-2.5 text-sm text-white focus:border-red-400 focus:outline-none">
            @error('password') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="w-full rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-500">Confirm Password</button>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="block text-center text-sm text-red-300 hover:text-red-200">Forgot Your Password?</a>
        @endif
    </form>
</x-auth.shell>
@endsection
