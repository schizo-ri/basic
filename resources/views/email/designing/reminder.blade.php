@component('mail::message')
# Dokumentacija na projektu {!! $designing->project_no !!} na ormar {!! $designing->cabinet_name !!}

Poštovani, budući da se bliži rok isporuke ormara i tehničke dokumentacije, molim Vas da nam dostavite dokumentaciju koja nedostaje kako bi se mogla završiti tehnička dokumentacija ormara.

**Nedostaje:**<br>
@foreach ($missing_doc as $key => $doc)
{!! $doc !!}<br>
@endforeach

@component('mail::button', ['url' => $link])
Proizvodnja
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent