@component('mail::message')
# Prijava greške

@component('mail::panel')
host:  {!! $url . "\r\n"  . "\r\n" !!}
request uri:  {!! $request_uri ."\r\n"   . "\r\n" !!}
exception: {!! $request['exception'] . "\r\n"  . "\r\n" !!}
message: {!! $request['message'] ."\r\n"   . "\r\n" !!}
file: {!! $request['file'] . "\r\n"  . "\r\n" !!}
line: {!! $request['line'] . "\r\n"  . "\r\n" !!}
user email {!! $user_mail  !!}
@endcomponent

{{ $user }}

@endcomponent
