@component('mail::message')
Zahtjev za dan {{ date("d.m.Y", strtotime($afterhour->date)) }} je obrađen.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
