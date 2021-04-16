@component('mail::message')
#  Za dan {!! date('d.m.Y') !!} dodijeljen ti je slijedeći projekt

Broj projekta: {!! $designing->project_no !!}

Naziv projekta: {!! $designing->name !!}

Ormar: {!! $designing->cabinet_name !!}

Voditelj: {!! $designing->manager ? $designing->manager->first_name . ' ' . $designing->manager->last_name : '' !!}

Napomena: {!! $designing->comment !!}

@component('mail::button', ['url' => $link])
    Proizvodnja
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
