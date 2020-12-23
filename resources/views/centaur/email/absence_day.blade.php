<!DOCTYPE html>
<html lang="hr">
	<head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{ URL::asset('css/absence.css') }}"/>
	</head>
	@include('Centaur::mail_style')
	<body>
        @lang('absence.absences')  
        @foreach($day_absences as $day_absence)
            <p>{{ $day_absence->absence['zahtjev'] . ', ' . $day_absence['ime'] . ', ' . (string)$day_absence['period'] . ', ' . $day_absence['vrijeme'] }}</p>
        @endforeach
    </body>
</html>