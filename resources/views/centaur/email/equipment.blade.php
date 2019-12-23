<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<p>Djelatnik {{ Sentinel::getUser()->email }} je obnovio popis opreme za projekt {{ $preparation->project_no . ' ' . $preparation->name }}</p>
		<p>Zapise možete provjeriti na <a href="{{ $link }}">linku</a></p>
	</body>
</html>