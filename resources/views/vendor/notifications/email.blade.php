@component('mail::message')
# @lang('Hola!!')

Anda menerima email ini karena kami menerima permintaan pengaturan ulang kata sandi untuk akun Anda. Tekan tombol di bawah ini untuk mengatur ulang kata sandi.

@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent

Tautan Pengaturan Ulang Reset Kata Sandi ini akan kedaluwarsa dalam waktu 60 menit. Jika Anda tidak meminta pengaturan ulang kata sandi, abaikan saja pesan ini.

@lang('Salam,'),<br>
Sarastya Technology

@slot('subcopy')
@lang(
    "Jika Anda kesulitan mengakses tombol \":actionText\", salin dan tempel URL di bawah ini ke browser web Anda:",
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
@endslot

@endcomponent
