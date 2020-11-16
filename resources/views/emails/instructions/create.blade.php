@component('mail::message')
# Na myIntranetu je objavljena nova radna uputa

{!! $instruction->title !!}

@component('mail::button', ['url' => $link])
Vidi detalje
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent
