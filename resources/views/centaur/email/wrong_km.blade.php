@component('mail::message')
# @lang('basic.wrong_km')

@lang('basic.employee') {{ $user->first_name . ' ' . $user->last_name . ' prijavio je pogrešne početne kilometre' }}
{{ $car->registration . ' - datum vožnje: ' . date('d.m.Y',strtotime($locco->date)) }}
@component('mail::panel')

{{ $napomena }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
