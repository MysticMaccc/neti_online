@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://netionline.neti.com.ph/assets/images/oesximg/logo.png" width="150" height="auto" alt="">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
