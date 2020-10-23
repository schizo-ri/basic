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
		<h4>{{ __ ('emailing.with_day') . ' ' . date("d.m.Y", strtotime($employee->reg_date)) . ' ' . __ ('emailing.employed_worker') . ' ' . $employee->user['first_name'] . ' ' . $employee->user['last_name'] . ' ' .  __ ('emailing.in_the_workplace') . ' ' . $employee->work['name'] }}</h4>
		<br/>
		<div style="margin-bottom: 20px">
			<p><b>@lang('basic.comment'): </b></p>
			<p style="padding-left: 20px">{{ $employee->comment }}</p>
		</div>
		<div style="margin-bottom: 20px">
			<p><b>IT odjel: </b>Molim kontaktirati {{ $employee->user['first_name'] . ' ' . $employee->user['last_name'] }} za sve potrebne informacije.</p>
		</div>
	</body>
</html>