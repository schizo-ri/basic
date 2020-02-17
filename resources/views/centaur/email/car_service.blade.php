@component('mail::message')
# @lang('basic.malfunction')

@lang('basic.employee') {{ $user->first_name . ' ' . $user->last_name . ' prijavio je kvar na vozilu ' }}
{{ $car->registration }}
@component('mail::panel')
{{ $napomena }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
