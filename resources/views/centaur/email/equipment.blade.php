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
		<p>Djelatnik {{ Sentinel::getUser()->email }} je obnovio popis opreme za projekt {{ $preparation->project_no . ' ' . $preparation->name }}</p>
		
		<p><b>Lista prije promjene:</b></p>
		<table>
			<thead>
				<tr >
					<th>Produkt</th>
					<th>Oznaka</th>
					<th>Naziv</th>
					<th>količina</th>
					<th>Isporučeno</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($before_all as $item)
					<tr class="{{ $item['class'] }}" >
						<td>{{ $item['product_number'] }}</td>
						<td>{{ $item['mark'] }}</td>
						<td>{{ $item['name'] }}</td>
						<td class="quantity_b">{{ $item['quantity']}}</td>
						<td class="delivered_b">{{ $item['delivered']  }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		<p><b>Lista poslije promjene: </b></p>
		<table>
			<thead>
				<tr>
					<th>Produkt</th>
					<th>Oznaka</th>
					<th>Naziv</th>
					<th>količina</th>
					<th>Isporučeno</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($after_all as $item)
					<tr class="{{ $item['class'] }}" >
						<td>{{ $item['product_number'] }}</td>
						<td>{{ $item['mark'] }}</td>
						<td>{{ $item['name'] }}</td>
						<td class="quantity_a">{{ $item['quantity']}}</td>
						<td class="delivered_a">{{ $item['delivered']  }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		<p>Zapise možete provjeriti na <a href="{{ $link }}">linku</a></p>		
	</body>
</html>