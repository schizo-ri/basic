<!DOCTYPE html>
<html lang="hr">
	<head>
        <meta charset="utf-8">
		<style>
			body { 
				font-family: DejaVu Sans, sans-serif;
			}
            #mail_template #header, #mail_template #footer {
                height: auto;
                border: none;
                padding: 10px 15px;
				text-align: center;
				clear: both;
				overflow-wrap: break-word;
            }
			#mail_template #header {
				font-size: 16px;
				font-weight: bold;
			}
			#mail_template #footer {
				font-size: 12px;
			}
            #mail_template #body {
                height: auto;
                border: none;
                font-size: 14px;
				padding: 15px;
				clear: both;
				overflow-wrap: break-word;
				line-height: 16px;
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
				font-size:12px;
				margin:15px;
				float:left;
				cursor:pointer
			}
			.marg_20 {
				margin-bottom:20px;
			}
			.marg_top_20 {
				margin-top:20px;
			}
        </style>
	</head>
	<body>
        <div style="width: 500px;max-width:100%;margin:auto;" id="mail_template">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="header" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_header : '' !!}">
                <p>Zahtjev za odobrenje prekovremenih sati</p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_body : '' !!}">
				<h4>Ja, {{ $afterhour->employee->user->first_name . ' ' . $afterhour->employee->user->last_name }} molim da mi se potvrdi izvršeni prekovremeni rad <br>
					za projekt: {{ $afterhour->project->erp_id . ' - ' . $afterhour->project->name }}<br>
					za {{ date("d.m.Y", strtotime($afterhour->date)) . ' od ' . $afterhour->start_time  . ' do ' .  $afterhour->end_time }}</h4>
				<div><b>Napomena: </b></div>
				<div class="marg_20">
					{{ $afterhour->comment }}
				</div>		
				<form method="get" target="_blank" action="{{ route('confirmationAfterHours') }}">
					<input style="height: 34px;width: 100%; border-radius: 5px; border: 1px solid #ccc;" type="text" name="approved_reason" maxlength="191"><br>
					<input type="hidden" name="id" value="{{ $afterhour->id }}"><br>
					<div class="time">
						<label>Odobreno prekovremenih sati:</label>
						<input name="approve_h" class="date form-control" type="time" value="{!! isset( $interval ) ? $interval : '00:00' !!}" id="date1" required><i class="far fa-clock" style="border-radius: 5px; border: 1px solid #ccc"></i></i>
					</div>
					<input type="radio" name="approve" value="1" id="approve1" checked>  <label for="approve1" style="cursor:pointer">Potvrđeno</label>
					<input type="radio" name="approve" value="0" id="approve2" style="padding-left:20px;"> <label for="approve2" style="cursor:pointer">Odbijeno</label><br>
					{{ csrf_field() }}
					<input class="odobri" type="submit" value="Pošalji">
				</form>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="footer"  style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_footer : '' !!}">
				<p>{{ config('app.name') }}</p>
            </div>
        </div>
	</body>
</html>