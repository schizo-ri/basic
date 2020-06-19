@component('mail::message')
# Prijava greške

@component('mail::panel')
    {!! 'message' . ': ' . $request !!}
@endcomponent

@component('mail::panel')
    {!! 'request_uri' . ': ' .$request_uri !!}
@endcomponent

@component('mail::button', ['url' => $url])
Link
@endcomponent


{{ $user }}
@endcomponent
