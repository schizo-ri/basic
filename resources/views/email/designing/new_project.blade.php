@component('mail::message')
# Voditelj projekta {!! $designing->manager->first_name . ' ' . $designing->manager->last_name !!} te je odabrao za zadatak.

Broj projekta: {!! $designing->project_no !!}

Naziv projekta: {!! $designing->name !!}

Voditelj: {!! $designing->manager->first_name . ' ' . $designing->manager->last_name !!}

Projektant: {!! $designing->designer->first_name . ' ' . $designing->designer->last_name !!}

napomena: {!! $designing->comment !!}


@component('mail::button', ['url' => $link])
Proizvodnja
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
