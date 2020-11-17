@component('mail::message')
# {!! $title !!}

@component('mail::panel')
    @foreach($day_absences as $day_absence)
        {!! $day_absence['zahtjev'] . ', ' . $day_absence['ime'] . ', ' . (string)$day_absence['period'] . ', ' . $day_absence['vrijeme'] !!} <br>
    @endforeach
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent