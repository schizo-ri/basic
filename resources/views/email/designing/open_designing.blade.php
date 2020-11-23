@component('mail::message')
# U projektiranju je otvoren novi projekt:

Broj projekta: {!! $designing->project_no !!}

Naziv projekta: {!! $designing->name !!}

Voditelj: {!! $designing->manager ? $designing->manager->first_name . ' ' . $designing->manager->last_name : '' !!}

Napomena: {!! $designing->comment !!}

@component('mail::button', ['url' => $link])
Proizvodnja
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
