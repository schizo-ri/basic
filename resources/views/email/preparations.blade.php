<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8"/>
		<style>	
			table {
				font-size: 14px;
				border: 1px solid #ccc;
				margin: 10px 20px 30px;
			}
			thead th{
				font-weight: 500;
				font-size: 12px;
				border-bottom: 1px solid #ccc;
				padding: 5px 10px;
			}
			tbody tr>td {
				padding: 5px 10px;
			}			
			.partial {
				background-color: #ffff33;
			}
			.all_delivered {
				background-color: #84e184;
			}
			.not_delivered {
				background-color: #ff8080;
			}
		</style>
	</head>
	<body>
		<p>U protekla 24 sata ovoreneni su slijedeći projekti</p>
		<br>
		<table>
			<thead>
				<tr >
					<th>Broj</th>
					<th>Naziv projekta</th>
					<th>Naziv ormara</th>
					<th>Voditelj</th>
					<th>Datum isporuke</th>
				</tr>
			</thead>
			<tbody>
				@if (count($preparations))
					@foreach ($preparations as $preparation)
						<tr >
							<td>{{ $preparation['project_no'] }}</td>
							<td>{{ $preparation['project_name'] }}</td>
							<td>{{ $preparation['name'] }}</td>
							<td>{{ $preparation['project_manager'] }}</td>
							<td>{{ $preparation['date'] }}</td>
						</tr>
					@endforeach
				@endif
			</tbody>
		</table>
	
		<p>Zapise možete provjeriti na <a href="https://proizvodnja.duplico.hr/preparations">linku</a></p>		
	</body>
</html>