<!DOCTYPE html>
<html>
	<head>
		<title>Zahtjev {{ $absence->employee->user->last_name }}</title>
		<style>
			body {
				height: 100vh;
				width: auto;
				max-width: 800px;
				margin: auto;
				font-family: Arial;
				font-size: 16px;
			}
			header, footer, body {
				overflow: hidden;
			}
			.container {
				height: 100%;
			}	
			.conta
			.zahtjevPrint {
				max-width: 800px;
				margin: auto;
				padding: 20px 30px;
				height: 100%;
			}
			.zahtjevPrint header {
				margin: 100px 0;
			}
			.zahtjevPrint h2 {
				text-align: center;
				margin: 20px 0;
				font-size: 20px;
			}
			h2 span {
				text-transform: uppercase;
				margin-bottom: 20px;
				display: inline-block;
				
			}
			.zahtjevPrint main {
				margin-bottom: 100px;
			}
			main p, .odobrio p, .datum p {
				margin: 10px 0;
			}
			.zahtjevPrint .datum {
				width: 30%;
				margin-bottom: 100px;
			}
			footer .odobrio {
				width: 50%;
				float: right;
				padding-top: 50px;
			}
			</style>
	</head>
	<body>
		<div class="container">
			<div class="zahtjevPrint">
				<img src="{{ URL::asset('storage/company_img/logo.png')}}" alt="company_logo" style="max-width: 20%" />
				<header>
					<h2><span>Zahtjev</span><br>
					za {{  $absence->absence->name }} </h2>
				</header>
				<main>
					<p>Ja <b>{{ $absence->employee->user->first_name . ' ' .  $absence->employee->user->last_name }}</b> molim da mi se odobri {{ $absence->absence->name }}</p>
					<p>u periodu od {{ date('d.m.Y', strtotime( $absence->start_date )) }} do  {{date('d.m.Y', strtotime( $absence->end_date ))   }} u trajanju od {{ $daniGO }} radnih dana.</p>
				</main>
				<footer>
					<div class="datum">
						<p>{{date('d.m.Y', strtotime( $absence->created_at))  }} </p>
						<span><small>(Datum podnošenja zahtjeva)</small></span>
					</div>
					<p class=""><small>Ovaj zahtjev podnesen je osobno od strane radnika elektroničkim putem te je kao takav valjan bez potpisa radnika</small></p>
					<div class="odobrio">
						<p>Zahtjev odobrio: {!! $absence->approved ? $absence->approved->user->first_name . ' ' . $absence->approved->user->last_name : '' !!}</p>
						<p>dana: @if($absence->approved_date){{ date('d.m.Y', strtotime( $absence->approved_date )) }} @endif</p>
					</div>
				</footer>
			</div>
		</div>
	</body>
</html>
