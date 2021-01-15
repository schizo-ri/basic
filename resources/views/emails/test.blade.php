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
                overflow: hidden;
                line-height: 16px;
            }
            .MsgBody .Object {
				margin: 0;
    			display: inline-block;
			}
			.MsgBody .Object-hover {
				margin:0;
			}
            .odobri, .link {
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
        </style>
	</head>
	<body>
        <div style="width: 500px;max-width:100%;margin:auto;" id="mail_template">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="header" style="{!! $mail_style ? $mail_style->style_header : '' !!}">
				@if(count($text_header) > 0)
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
					<p>Lorem Ipsum</p>
				@endif
            </div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $mail_style ? $mail_style->style_body : '' !!}">
				@if(count($text_body) > 0)
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
						<p style="{{ $style }}">{{ sprintf($text, $variable) }}</p>
					@endforeach
                @else
					<p>What is Lorem Ipsum? Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p>
					<p>{{ $variable }}</p>
				@endif
            </div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="footer" style="{!! $mail_style ? $mail_style->style_footer : '' !!}" >
				@if(count($text_footer) > 0)
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