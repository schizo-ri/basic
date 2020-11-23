@component('mail::message')
# Na projektu {!! $designing->project_no . ' ' . $designing->name !!} zadužen je projektant {!! $designing->designer->first_name . ' ' . $designing->designer->last_name !!}

@component('mail::button', ['url' => $link])
Proizvodnja
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
