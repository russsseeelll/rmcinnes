@component('mail::message')
# Hello {{ $name }},

{{ $message }}

Thanks,<br>
{{ $profile->name }}
@endcomponent
