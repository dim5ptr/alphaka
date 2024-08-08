<x-mail::message>
{{-- Logo --}}
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hola!!')
@endif
@endif


{{-- Intro Lines --}}
<br>Anda menerima email ini karena kami menerima permintaan pengaturan ulang kata sandi untuk akun Anda. Tekan tombol di bawah ini untuk mengatur ulang kata sandi.

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Outro Lines --}}
Tautan Pengaturan Ulang Reset Kata Sandi ini akan kedaluwarsa dalam waktu 60 menit. Jika Anda tidak meminta pengaturan ulang kata sandi, abaikan saja pesan ini.
<br><br>

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
Salam,<br>
Sarastya Technology
@endif

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
@lang(
    "Jika Anda kesulitan mengakses tombol \":actionText\", salin dan tempel URL di bawah ini ke browser web Anda:",
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
    <div style="text-align: center; margin-top: 20px;">
        <p>&copy; {{ date('Y') }} Sarastya. All rights reserved.</p>
    </div>
</x-slot:footer>
</x-mail::message>
