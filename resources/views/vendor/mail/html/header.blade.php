@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@php
    /** 
     * Get the raw path from your Setting model 
     * using the key 'site_logo'
     */
    $rawPath = \App\Models\Setting::get('logo');

    /**
     * Use your working logic to generate the full URL
     */
    $logoUrl = $rawPath ? url('storage/' . $rawPath) : null;
@endphp

@if ($logoUrl)
    <img src="https://pskoutfithub.com/storage/settings/nYuAoYZmY4h2DZaFiflNAT7SxSvxcUTL0GmlzcLM.png" class="logo" alt="{{ config('app.name') }} Logo" style="max-width: 300px;">
@else
    {{-- Fallback: show the App Name as text if the logo is missing in settings --}}
    {{ config('app.name') }}
@endif
</a>
</td>
</tr>