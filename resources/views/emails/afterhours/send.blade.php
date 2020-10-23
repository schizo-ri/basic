@component('mail::message')
Uspješno ste poslali zahtjev za dan {{ date('d.m.Y',strtotime($afterhour->date)) }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
