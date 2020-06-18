@component('mail::message')
# Prijava greške
{{ $user  . "\r\n" }}

@component('mail::panel')
user email {!! $user_mail ."\r\n"   . "\r\n" !!}
host:  {!! $url . "\r\n"  . "\r\n" !!}
request uri:  {!! $request_uri ."\r\n"   . "\r\n" !!}
message: {!! str_limit($request,500)  !!}

@endcomponent

@endcomponent