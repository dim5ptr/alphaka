@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            <img src="{{ asset('https://avatars.githubusercontent.com/u/156770999?s=200&v=4') }}" alt="{{ config('app.name') }}" style="max-width: 100px;">
        </a>
    </td>
</tr>
