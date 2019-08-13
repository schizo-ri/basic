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
		<div><b>@lang('basic.comment'): </b></div>
		<div>
			{{ $employee->comment }}
		</div>
	</body>
</html>