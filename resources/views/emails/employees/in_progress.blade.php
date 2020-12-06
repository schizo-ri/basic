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

            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_body : '' !!}">
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
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="footer"  style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_footer : '' !!}">
				<p>{{ config('app.name') }}</p>
            </div>
        </div>
	</body>
</html>