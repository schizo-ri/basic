@component('mail::message')
# Djelatniku {{ $employeeTraining->employee->user->first_name . ' ' . $employeeTraining->employee->user->last_name }} osposobljavanje ističe {{ date('d.m.Y', strtotime($employeeTraining->expiry_date )) }}!

<br>
{{ config('app.name') }}
@endcomponent
