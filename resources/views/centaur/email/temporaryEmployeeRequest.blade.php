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
                <p></p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_body : '' !!}">
                <h4> @lang('absence.i'), {{ $temporaryEmployeeRequest->employee->user['first_name']   . ' ' . $temporaryEmployeeRequest->employee->user['last_name'] }}</h4>
                <h4>
                    
                    @lang('absence.please_approve')  {{ $temporaryEmployeeRequest->absence['name'] }} za
                    {{ date("d.m.Y", strtotime($temporaryEmployeeRequest->start_date)) . ' do ' . date("d.m.Y", strtotime( $temporaryEmployeeRequest->end_date)) . ' - ' . $dani_zahtjev . ' ' . __ ('absence.days') }}
                
                    @if( $temporaryEmployeeRequest->absence['mark'] == "IZL")
                        {{  'od ' . $temporaryEmployeeRequest->start_time  . ' - ' .  $temporaryEmployeeRequest->end_time }}
                    @endif
                </h4>
                <div><b>@lang('basic.comment'): </b></div>
                <div class="marg_20">
                    {{ $temporaryEmployeeRequest->comment }}
                </div>
                <form name="contactform" method="get" target="_blank" action="{{ route('confirmationTemp') }}">
                    <input style="height: 34px;width: 100%;border-radius: 5px;" type="text" name="approve_reason" value=""><br>
                    <input type="hidden" name="id" value="{{ $temporaryEmployeeRequest->id }}"><br>
                    <input type="radio" name="approve" value="1" checked> @lang('absence.approved')
                    <input type="radio" name="approve" value="0" style="padding-left:20px;">  @lang('absence.not_approved')<br>
                    <input type="hidden" name="email" value="1" checked><br>
                    <input class="odobri marg_top_20" type="submit" value="{{ __('basic.send_mail') }}">
                </form>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="footer"  style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_footer : '' !!}">
                <p>{{ config('app.name') }}</p>
            </div>
        </div>
	</body>
</html>
		