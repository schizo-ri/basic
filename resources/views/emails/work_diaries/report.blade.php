<!DOCTYPE html>
<html lang="hr">
	<head>
        <meta charset="utf-8">
		<style>
			html {
				overflow: auto;
			}
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
				font-size: 15px;
				font-weight: bold;
			}
			#mail_template #footer {
				font-size: 12px;
			}
			#mail_template #body {
				height: auto;
				border: none;
				font-size: 13px;
				padding: 15px;
				clear: both;
				overflow-wrap: break-word;
				/* overflow: hidden; */
				line-height: 16px;
			}
			.MsgBody .Object {
				margin: 0;
    			display: inline-block;
			}
			.MsgBody .Object-hover {
				margin:0;
			}
			.odobri, .link { cursor: pointer;
				min-width: 100px;
				height: auto;
				background-color: white;
				border: 1px solid #cccccc;
				border-radius: 5px;
				box-shadow: 5px 5px 8px #cccccc;
				text-align: center !important;
				padding: 10px;
				background: #007cc3 !important;
				color: white !important;
				font-weight: bold;
				font-size: 12px;
				margin: 10px;
				float: left;
				margin-left: 0;
			}
			.link{
				min-width:100px;
				background-color:white;
				border: 1px solid #cccccc;
				border-radius: 5px;
				box-shadow: 5px 5px 8px #cccccc;
				text-align: center !important;
				padding:10px;
				color:#007cc3 !important;
				font-weight:bold;
				margin:15px;
				cursor:pointer;
			}
			.marg_20 {
				margin-bottom:20px;
			}
			.marg_top_20 {
				margin-top:20px;
			}
			.company_logo {
                max-height: 20px;
				max-width: 85px;
				margin: 10px 0;
			}
			div p {
				margin: 0;
				padding: 5px;
			}
			.align_c {
				text-align: center;
			}
			table {
				font-size: 14px;
				border: 1px solid #ccc;
				margin: 10px 0;
				overflow: auto;
			}
			th:not(:last-child) , td:not(:last-child) {
				border-right: 1px solid #ccc;
			}
		
			td,th {
				width: 8%;
				border-bottom: 1px solid #ccc;
			}
			.bg_blue {
				background: rgb(25, 75, 184);
			}
			.bg_lightblue {
				background: #bbccf3;
			}
			.bg_darkblue {
				background: #0a2c77;
			}
			.bg_grey {
				background: #ddd;
			}
			#mail_template #body.diary_body {
				width: 100%;
    			overflow: auto;
			}
			.color_white {
				color: white;
			}
			.padd10_0 {
				padding: 10px 0;
			}
		</style>
	</head>
	<body>
        <div style="width: 500px;max-width:100%;margin:auto;padding: 20px;"  id="mail_template">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="header" style="{!! $template_mail && $template_mail->mailStyle ? $template_mail->mailStyle->first()->style_header : '' !!}">
				@if( isset($text_header) && $text_header && count($text_header) > 0)
					@foreach ($text_header as $key => $text)
						@php
							$style = '';
							if( $mail_style && $mail_style->style_header_input ) {
								$el_style = explode('|', $mail_style->style_header_input );
							}
							if( isset($el_style) ) {
								if( count($el_style) > 0 && isset( $el_style[ $key ])) {
									$style = $el_style[ $key ];
								} else {
									$style = $el_style[0];
								}
							}
						@endphp
						<p style="{{ $style }}">{{ $text }}</p>
					@endforeach
				@else
                    <p>Dnevnik rada - izvještaj za {{ date('d.m.Y') }}</p>
                    <p>Projekt: {{ $workDiaries->first()->project ? '['.  $workDiaries->first()->project->erp_id . '] ' . $workDiaries->first()->project->name : ''  }}</p>
				@endif
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 diary_body" id="body" style="{!! $template_mail && $template_mail->mailStyle ? $template_mail->mailStyle->first()->style_body : '' !!}">
				@if( isset($text_body) && $text_body && count($text_body) > 0)
					@foreach ($text_body as $key => $text)
						@php
							$style = '';
							if( $mail_style && $mail_style->style_body_input ) {
								$el_style = explode('|', $mail_style->style_body_input );
							}
							if( isset($el_style) ) {
								if( count($el_style) > 0 && isset($el_style[ $key ])) {
									$style = $el_style[ $key ];
								} else {
									$style = $el_style[0];
								}
							}
						@endphp
						<p style="{{ $style }}">{{ $text }}</p>
					@endforeach
				@else
					<table>
						<thead>
							<tr>
								<th>Djelatnik</th>
								<th>Vrijeme</th>
								{{-- @foreach ($workTasks as $tasks)
									<th>{{ $tasks->name }}</th>
								@endforeach
								<th>Ukupno</th> --}}
							</tr>
						</thead>
						<tbody>
							@php
								$all_time_project = 0;
							@endphp
							@foreach ($workDiaries as $workDiary)
								@php
									$seconds = 0;
									$items = $workDiary->hasWorkDiaryItem;
								@endphp
								<tr>
									<td colspan="2" class="bg_blue color_white">{{ $workDiary->employee->user->first_name . ' '.  $workDiary->employee->user->last_name }}</td>
								</tr>
								@foreach ($items as $item)
									@php
										if( $item ) {
											list($hour,$minute) = explode(':', $item->time);
											$seconds += $hour*3600;
											$seconds += $minute*60;																	
										}
									@endphp
									<tr>
										<td>{{ $item->workTask->name }}</td>
										<td class="">{{ date('H:i', strtotime($item->time)) }}</td>
									</tr>
								@endforeach
								<tr>
									<td  colspan="2" class="bg_lightblue">Ukupno vrijeme: {{ round(($seconds/3600),2 ) }} h</td>
									@php
										$all_time_project += $seconds;
									@endphp
								</tr>
							@endforeach
						</tbody>
						<tfoot class="bg_darkblue color_white ">
							<tr>
								<td colspan="2" class="padd10_0">Ukupno vrijeme projekta: {{  round(($all_time_project  / 3600),2) .' h' }}</td>
							</tr>
						</tfoot>
					</table>
                @endif
            </div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="footer"  style="{!! $template_mail && $template_mail->mailStyle ? $template_mail->mailStyle->first()->style_footer : '' !!}">
				@if( isset($text_footer) && $text_footer && count($text_footer) > 0)
					@foreach ($text_footer as $key => $text)
						@php
							$style = '';
							if( $mail_style && $mail_style->style_footer_input ) {
								$el_style = explode('|', $mail_style->style_footer_input );
							}
							if( isset($el_style) ) {
								if( count($el_style) > 0 && isset($el_style[ $key ])) {
									$style = $el_style[ $key ];
								} else {
									$style = $el_style[0];
								}
							}
						@endphp
						<p style="{{ $style }}">{{ $text }}</p>
					@endforeach
                @endif
                @if(file_exists(public_path() . '/storage/company_img/logo.png'))
                    <img src="{{ URL::asset('storage/company_img/logo.png')}}" alt="company_logo" class="company_logo"/>
                @else
                    <p>{{ config('app.name') }}</p>
                @endif
            </div>
        </div>
	</body>
</html>