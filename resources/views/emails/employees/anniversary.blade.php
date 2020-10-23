@component('mail::message')
# Djelatnik {{ $employee->first_name . ' ' . $employee->last_name }} ima {{ $dana }} {{ $years }}. godišnjicu rada u firmi!

Datum prijave: {{ date("d.m.Y", strtotime($employee->reg_date)) }}

<br>
{{ config('app.name') }}
@endcomponent
