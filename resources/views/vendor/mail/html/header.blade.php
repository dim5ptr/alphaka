@props(['url'])
<tr>
    <td class="header">
    <a href="{{ $url }}" style="display: inline-block;">
    @if (trim($slot) === 'Laravel')
    {{ asset('img/logo_sti.png') }}
    <img src="{{ asset('img/logo_sti.png') }}" alt="{{ config('app.name') }}" style="max-width: 100px;">
    @else
    {{ $slot }}
    @endif
    </a>
    </td>
</tr>
