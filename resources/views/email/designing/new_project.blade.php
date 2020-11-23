@component('mail::message')
# {!! Sentinel::getUser()->first_name . ' ' . Sentinel::getUser()->last_name !!} te je odabrao za zadatak na slijedećem projektu:

Broj projekta: {!! $designing->project_no !!}

Naziv projekta: {!! $designing->name !!}

Voditelj: {!! $designing->manager ? $designing->manager->first_name . ' ' . $designing->manager->last_name : '' !!}

Projektant: {!! $designing->designer ? $designing->designer->first_name . ' ' . $designing->designer->last_name : '' !!}

Napomena: {!! $designing->comment !!}


@component('mail::button', ['url' => $link])
Proizvodnja
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
