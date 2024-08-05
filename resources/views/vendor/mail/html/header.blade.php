@props(['url'])

<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
                <img src="{{ asset('img/logo_sti.svg') }}" alt="{{ config('app.name') }}" style="max-width: 100px; height: auto;">
            @else
                <h4>Sarastya</h4>
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>