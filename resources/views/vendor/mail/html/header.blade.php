@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="img/logo_sti.png" class="logo" alt="Sarastya Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
