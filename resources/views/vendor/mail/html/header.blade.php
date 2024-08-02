@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://photos.google.com/photo/AF1QipMmb_LkjgaO_KJHPFmBhJQvTcOiC9Hq-Pbruul9" alt="{{config('app.name')}}">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
