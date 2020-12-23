@extends('Centaur::admin')

@section('title', __('basic.add_mail_templates'))

@section('content')
	<form accept-charset="UTF-8" role="form" method="post" class="mail_form" action="{{ route('mail_templates.update', $mailTemplate->id ) }}" >
		<header class="page-header">
			<p class="mail_tamplate_title">@lang('basic.edit_mail_templates')
				{{ csrf_field() }}
				{{ method_field('PUT') }}
				<input class="btn-submit" type="submit" value="{{ __('basic.edit')}}">
			</p>
		</header>
		<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="table-responsive">
				<section class="col-xs-6 col-sm-6 col-md-6 col-lg-6 float_left" id="style_items">
					<section>
						<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
							<input class="form-control" name="name" type="text" value="{{ $mailTemplate->name }}" placeholder="{{ __('basic.name') }}" required />
							{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
							<textarea class="form-control" name="description" type="text" placeholder="{{ __('basic.description') }}" required >{{ $mailTemplate->description }}</textarea>
							{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('for_mail')) ? 'has-error' : '' }}">
							<select class="form-control" name="for_mail" value="{{ old('for_mail') }}" required >
								@foreach ($docs as $doc)
									@if ($mailTemplate->for_mail == strstr($doc, '.', true))
										<option value="{{ strstr($doc, '.', true) }}" selected'>{{ strstr($doc, '.', true) }}</option>
									@endif
								@endforeach
							</select>
						</div>
					</section>
					@foreach ($elements as $element)
						<div class="form-group {{ $element }}" hidden>
							@php
								if( $element == 'header') {
									$style = $header;
								} else if( $element == 'body') {
									$style = $body;
								} else if( $element == 'footer') {
									$style = $footer;
								}
							@endphp
							<h5>{{ ucfirst( $element) }}</h5>
							<p>Font</p>
							<div>
								<label>Boja <input name="{{ $element }}[color]" type="color" id="color[{{ $element }}]" class="color_{{ $element }}" value="{{ $style['color'] }}"/></label>
								<label>Veličina
									<select name="{{ $element }}[font-size]" id="font-size[{{ $element }}]" class="font-size_{{ $element }}" >
										@for ( $i = 8; $i < 30; $i++)
											<option value="{{ $i }}px" {!! $i.'px' == $style['font-size'] ? 'selected' : '' !!}>{{ $i }}</option>
										@endfor
									</select>
								</label>
								<label>Poravnanje 
									<select name="{{ $element }}[text-align]" id="text-align[{{ $element }}]" class="text-align_{{ $element }}" >
										<option value="left" {!! $style['text-align'] == "left" ? 'selected' : ''!!}>Lijevo</option>
										<option value="center" {!! $style['text-align'] == "center" ? 'selected' : ''!!} >Sredina</option>
										<option value="right" {!! $style['text-align'] == "right" ? 'selected' : ''!!}>Desno</option>
									</select>
								</label>
								<label>Uvlaka
									<select name="{{ $element }}[padding-left]" id="padding-left[{{ $element }}]" class="padding-left_{{ $element }}" >
										@for ( $i = 0; $i < 50; $i++)
											<option value="{{ $i }}px" {!!isset( $style['padding-left']) &&  $i.'px' == $style['padding-left'] ? 'selected' : '' !!}>{{ $i }}</option>
										@endfor
									</select>
								</label>
							</div>
							<p>Obrub</p>
							<div>
								<label>Boja <input name="{{ $element }}[border-color]" type="color" id="border-color[{{ $element }}]" class="border-color_{{ $element }}" value="{{ $style['border-color'] }}"/></label>
								<label>Debljina<input name="{{ $element }}[border-width]" type="number" min="0" max="4" id="border-width" class="border-width_{{ $element }}" value="{{ $style['border-width'] }}"/></label>
								<label>Stil
									<select name="{{ $element }}[border-style]" id="border-style[{{ $element }}]" class="border-style_{{ $element }}">
										<option value="dotted" {!! $style['border-style'] == "dotted" ? 'selected' : ''!!}>Točkasta</option>
										<option value="dashed" {!! $style['border-style'] == "dashed" ? 'selected' : ''!!} >Isprekidana</option>
										<option value="solid" {!! $style['border-style'] == "solid" ? 'selected' : ''!!}>Čvrsta</option> 
										<option value="double" {!! $style['border-style'] == "double" ? 'selected' : ''!!}>Dvostruka</option> 
										<option value="none" {!! $style['border-style'] == "right" ? 'none' : ''!!}>Bez</option> 
									</select>
								</label>
							</div>
							<p>Pozadina</p>
							<div>
								<label>Boja <input name="{{ $element }}[background-color]" type="color" id="background-color[{{ $element }}]" class="background-color_{{ $element }}" value="{{ $style['background-color'] }}"/></label>
							</div>
						</div>
					@endforeach
				</section>
				<section class="col-xs-6 col-sm-6 col-md-6 col-lg-6 float_left" id="mail_template">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="header" style="{{  $mailTemplate_style->style_header }} ">
						@foreach ($header_text as $key => $text1)
							<input name="text_header[text][{{$key}}]" type="text" id="text_header[text][[{{$key}}]" class="text_header" value="{{ $text1 }}" />
						@endforeach
						<span class="add_line">Dodaj liniju</span>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{{  $mailTemplate_style->style_body }} ">
						@foreach ($body_text as $key => $text1)
							<input name="text_body[text][{{$key}}]" type="text" id="text_body[text][[{{$key}}]" class="text_body" value="{{ $text1 }}" />
						@endforeach
						<span class="add_line">Dodaj liniju</span>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="footer" style="{{ $mailTemplate_style->style_footer }} ">
						@if(count($footer_text))
							@foreach ($footer_text as $key => $text1)
								<input name="text_footer[text][{{$key}}]" type="text" id="text_footer[text][[{{$key}}]" class="text_footer" value="{{ $text1 }}" />
							@endforeach
						@else
							<input name="text_footer[text][1]" type="text" id="text_footer[text][1]" class="text_footer" />
						@endif
						<span class="add_line">Dodaj liniju</span>
					</div>
				</section>
			</div>
		</main>
	</form>
	<script>
		$.getScript('/../js/mail_form.js');
	</script>
@stop