<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="utf-8">
	</head>
<style>
body { 
	font-family: DejaVu Sans, sans-serif;
	font-size: 10px;
}
</style>
	<body>
		<h4>@lang('emailing.in_progress') {{ $employee->user['first_name'] . ' ' . $employee->user['last_name'] . ' ' .  __('emailing.in_the_workplace') . ' ' . $employee->work['name']  }}</h4>

		<div style="margin-bottom: 20px">
			<p><b>@lang('basic.comment'): </b></p>
			<p style="padding-left: 20px">{{ $employee->comment }}</p>
		</div>
		<div style="margin-bottom: 20px">
			<p><b>Pravni odjel: </b>Molim pripremiti ugovornu dokumentaciju za radno mjesto: {{ $employee->work['name'] }}</p>
		</div>
		<div style="margin-bottom: 20px">
			<p><b>Odjel nabave: </b>Molim nabaviti potrebnu radnu opremu za radno mjesto {{ $employee->work['name'] }}</p>
			<p style="padding-left: 20px">Konfekcijski broj:  {{ $employee->size }}</p>
			<p style="padding-left: 20px">Veličina cipela: {{ $employee->shoe_size }}</p>
		</div>
	</body>
</html>