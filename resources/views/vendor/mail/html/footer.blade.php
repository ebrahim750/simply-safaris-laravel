<tr>
<td>
<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="content-cell" align="center">
<p class="footer-brand">{{ config('app.name') }}</p>
<p>{{ Illuminate\Mail\Markdown::parse($slot) }}</p>
<p>{{ now()->year }} {{ config('app.name') }}. All rights reserved.</p>
</td>
</tr>
</table>
</td>
</tr>
