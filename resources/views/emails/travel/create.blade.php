@component('mail::message')
# Putni nalog {!! $travel->destination !!}

Djelatnik {!! $travel->employee->user['first_name'] . ' ' . $travel->employee->user['last_name'] !!}

je otvorio putni nalog

Odredište: {!! $travel->destination !!}

@endcomponent
