@component('mail::message')
# @lang('absence.absences')  

@foreach($day_absences as $day_absence)
    <p>{{ $day_absence->absence['zahtjev'] . ', ' . $day_absence['ime'] . ', ' . (string)$day_absence['period'] . ', ' . $day_absence['vrijeme'] }}</p>
@endforeach

{{ config('app.name') }}
@endcomponent