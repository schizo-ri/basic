@component('mail::message')
# U tijeku je odjava radnika {{ $employeeTermination->employee->user->first_name . ' ' . $employeeTermination->employee->user->last_name }}, zaposlenom na radnom mjestu {{ $employeeTermination->employee->work->name }}.

Zadnji radni dan je {{date("d.m.Y", strtotime($employeeTermination->check_out_date)) }}.

<br>
{{ config('app.name') }}
@endcomponent
