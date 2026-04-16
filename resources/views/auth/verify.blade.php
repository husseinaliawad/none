@extends('layouts.app')

@section('content')
<x-auth.shell title="Verify Email" subtitle="Check your inbox and confirm your email to continue.">
    @if (session('resent'))
        <div class="mb-4 rounded-xl border border-emerald-400/30 bg-emerald-500/10 px-3 py-2 text-sm text-emerald-200">
            A fresh verification link has been sent to your email address.
        </div>
    @endif

    <p class="text-sm text-gray-300">Before proceeding, please check your email for a verification link. If you did not receive the email, request another one below.</p>

    <form method="POST" action="{{ route('verification.resend') }}" class="mt-4">
        @csrf
        <button type="submit" class="w-full rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-500">Resend Verification Email</button>
    </form>
</x-auth.shell>
@endsection
