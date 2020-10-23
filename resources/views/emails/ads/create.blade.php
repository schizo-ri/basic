@component('mail::message')
# Na oglasniku je objavljen novi oglas

Oglas možete pogledati na myIntranet oglasniku

@component('mail::button', ['url' => $url])
Vidi oglas
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
