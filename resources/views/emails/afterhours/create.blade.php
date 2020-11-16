<!DOCTYPE html>
<html lang="hr">
	<head>
		<meta charset="utf-8">
		<style>
			body { 
				font-family: DejaVu Sans, sans-serif;
				font-size: 10px;
				max-width:500px;
			}
			.odobri{
				width:150px;
				height:40px;
				background-color:white;
				border: 1px solid rgb(0, 102, 255);
				border-radius: 5px;
				box-shadow: 5px 5px 8px #888888;
				text-align:center;
				padding:10px;
				color:black;
				font-weight:bold;
				font-size:14px;
				margin:15px;
				float:left;
				custor:pointer
			}
			.marg_20 {
				margin-bottom:20px;
			}
			.time {
				position: relative;
				margin-bottom: 10px;
			}
			.time label {
				margin-right: 15px;
			}
			.time input {
				padding-left: 10px;
				height: 34px;
				width: auto;
				border-radius: 5px;
			}
			.time i {
				left: 10px;
				bottom: 10px;
				position: absolute;
				padding-left: 15px;
			}
		</style>
	</head>
	<body>
		<h4>Ja, {{ $afterhour->employee->user->first_name . ' ' . $afterhour->employee->user->last_name }} molim da mi se potvrdi izvršeni prekovremeni rad <br>
			za projekt: {{ $afterhour->project->erp_id . ' - ' . $afterhour->project->name }}<br>
			za {{ date("d.m.Y", strtotime($afterhour->date)) . ' od ' . $afterhour->start_time  . ' do ' .  $afterhour->end_time }}</h4>
		<div><b>Napomena: </b></div>
		<div class="marg_20">
			{{ $afterhour->comment }}
		</div>		
		<form method="get" target="_blank" action="{{ route('confirmationAfterHours') }}">
			<input style="height: 34px;width: 100%; border-radius: 5px; border: 1px solid #ccc;" type="text" name="approved_reason" maxlength="191"><br>
			<input type="hidden" name="id" value="{{$afterhour->id}}"><br>
			<div class="time">
				<label>Odobreno prekovremenih sati:</label>
				<input name="approve_h" class="date form-control" type="time" value="{!! isset( $interval ) ? $interval : '00:00' !!}" id="date1" required><i class="far fa-clock" style="border-radius: 5px; border: 1px solid #ccc"></i></i>
			</div>
			<input type="radio" name="approve" value="1" checked> Potvrđeno
			<input type="radio" name="approve" value="0" style="padding-left:20px;"> Odbijeno<br>
			{{ csrf_field() }}
			<input class="odobri" type="submit" value="Pošalji">
		</form>
	</body>
</html>
