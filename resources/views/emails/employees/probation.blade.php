@component('mail::message')
# Djelatniku {{ $employee->user->first_name . ' ' .  $employee->user->last_name }} ističe probni rok za  {{ $days }} dana!

Datum prijave: {{ date("d.m.Y", strtotime($employee->reg_date )) }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
