@component('mail::message')
# Putni nalog {{ $travel->destination }}

{!! $travel->date !!}
{!! $travel->employee->usee['first_name'] . ' ' . $travel->employee->usee['last_name'] !!}


@endcomponent
