@component('mail::message')
# Djelatniku {{ $employee->user->first_name . ' ' .  $employee->user->last_name }} dozvola za boravak ističe za  {{ $days }} dana!

Datum isteka dozvole za boravak: {{ date("d.m.Y", strtotime($employee->permission_date)) }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
