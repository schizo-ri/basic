@component('mail::message')
# Djelatnik {{ $employee->first_name . ' ' . $employee->last_name }} treba na liječnički pregled za {{ $days }} dana!

Datum isteka liječničkog uvjerenja: {{ date("d.m.Y", strtotime($employee->lijecn_pregled)) }}

<br>
{{ config('app.name') }}
@endcomponent
