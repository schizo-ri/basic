@component('mail::message')
# {!!  $preparationEmployee->user->first_name !!}, odabran/a si kao voditelj pripreme na slijedećem projektu:a

Broj projekta: {!! $preparationEmployee->preparation->project_no !!}

Naziv projekta: {!! $preparationEmployee->preparation->name !!}

Voditelj: {!! $preparationEmployee->preparation->manager ? $preparationEmployee->preparation->manager->first_name . ' ' . $preparationEmployee->preparation->manager->last_name : '' !!}

Projektant: {!! $preparationEmployee->preparation->designed ? $preparationEmployee->preparation->designed->first_name . ' ' . $preparationEmployee->preparation->designed->last_name : '' !!}

{!! $preparationEmployee->preparation->comment ? 'Napomena: ' . $preparationEmployee->preparation->comment : '' !!}


@component('mail::button', ['url' => $link])
Proizvodnja
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent