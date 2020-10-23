@component('mail::message')
# Djelatnik {{ $employee->first_name . ' ' . $employee->last_name }} slavi rođendan!

Datum rođenja: {{ date("d.m.Y", strtotime( $employee->b_day )) }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
