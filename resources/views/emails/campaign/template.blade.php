@component('mail::message')
# {!! $campaign->name !!}

{!! $campaign->description !!}

{!! $first_sequence->text !!}

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
