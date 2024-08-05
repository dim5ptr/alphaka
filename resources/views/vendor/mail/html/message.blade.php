<x-mail::layout>
    {{-- Header --}}
    <x-slot:header>
        <x-mail::header :url="config('app.url')">
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" style="height: 50px;">
        </x-mail::header>
    </x-slot:header>

    {{-- Body --}}
    <h4>Hello!</h4>
    <p>You are receiving this email because we received a password reset request for your account.</p>
        @component('mail::button', ['url' => $url])
        Reset Password
        @endcomponent
    <p>This password reset link will expire in 60 minutes.</p>
    <p>If you did not request a password reset, no further action is required.</p>

    <p>Regards,<br>
    {{ config('app.name') }}</p>

    {{-- Subcopy --}}
    @isset($subcopy)
    <x-slot:subcopy>
        <x-mail::subcopy>
            {{ $subcopy }}
        </x-mail::subcopy>
    </x-slot:subcopy>
    @endisset

    {{-- Footer --}}
    <x-slot:footer>
        <x-mail::footer>
            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
        </x-mail::footer>
    </x-slot:footer>
</x-mail::layout>
