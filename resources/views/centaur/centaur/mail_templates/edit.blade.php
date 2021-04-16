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
							<textarea class="form-control" name="description" type="text" placeholder="{{ __('basic.description') }}" rows="3" required >{{ $mailTemplate->description }}</textarea>
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
						@include('Centaur::mail_templates.edit_style', ['element' => $element ])
						@if( $element == 'header')
							@if(count($header_text) >0 )
								@foreach ($header_text as $key => $child_element)
									@include('Centaur::mail_templates.edit_style', ['element' => $element . '_'. 'input', 'count_input' => $key-1, 'style' => $header_input && isset($header_input[$key-1]) ? $header_input[$key-1] : null ])
								@endforeach
							@else
								@include('Centaur::mail_templates.edit_style', ['element' => $element . '_'. 'input', 'count_input' => 0, 'style' => null ])
							@endif
						@elseif( $element == 'body')
							@if(count($body_text) >0 )
								@foreach ($body_text as $key => $child_element)
									@include('Centaur::mail_templates.edit_style', ['element' => $element . '_'. 'input', 'count_input' => $key-1, 'style' => $body_input && isset($body_input[$key-1]) ? $body_input[$key-1] : '' ])
								@endforeach
							@else
								@include('Centaur::mail_templates.edit_style', ['element' => $element . '_'. 'input', 'count_input' => 0, 'style' => null ])
							@endif
						@elseif ( $element == 'footer')
							@if(count($footer_text) >0 )
							
								@foreach ($footer_text as $key => $child_element)
									@include('Centaur::mail_templates.edit_style', ['element' => $element . '_'. 'input', 'count_input' => $key-1, 'style' => $footer_input && isset($footer_input[$key-1]) ? $footer_input[$key-1] : '' ])
								@endforeach
							@else
								@include('Centaur::mail_templates.edit_style', ['element' => $element . '_'. 'input', 'count_input' => 0, 'style' => null ])
							@endif
						@endif
					@endforeach
				</section>
				<section class="col-xs-6 col-sm-6 col-md-6 col-lg-6 float_left" id="mail_template">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="header" style="{!! $mailTemplate_style && $mailTemplate_style->style_header ? $mailTemplate_style->style_header : '' !!} ">
						@if(count($header_text) >0 )
							@foreach ($header_text as $key => $text1)
								<input name="text_header[text][{{$key}}]" type="text" id="text_header[text][[{{$key}}]" class="text_header" value="{{ $text1 }}" style="{!! $header_input_style && isset($header_input_style[$key-1]) ? $header_input_style[$key-1] : '' !!}" />
							@endforeach
						@else
							<input name="text_header[text][1]" type="text" id="text_header[text][1]" class="text_header" placeholder="Unesi naslov"/>
						@endif
						<span class="add_line">Dodaj liniju</span>	
						<span class="add_link">Dodaj link</span>
						<span class="remove_line">Ukloni liniju</span>	
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $mailTemplate_style && $mailTemplate_style->style_body ? $mailTemplate_style->style_body : '' !!} ">
						@if(count($body_text) >0 )
							@foreach ($body_text as $key => $text1)
								<input name="text_body[text][{{$key}}]" type="text" id="text_body[text][[{{$key}}]" class="text_body" value="{{ $text1 }}" style="{!! $body_input_style && isset($body_input_style[$key-1]) ? $body_input_style[$key-1] : '' !!}"  />
							@endforeach
						@else
							<input name="text_header[text][1]" type="text" id="text_header[text][1]" class="text_header" placeholder="Unesi text"/>
						@endif
						<span class="add_line">Dodaj liniju</span>	
						<span class="add_link">Dodaj link</span>
						<span class="remove_line">Ukloni liniju</span>	
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="footer" style="{!! $mailTemplate_style && $mailTemplate_style->style_footer ?  $mailTemplate_style->style_footer : '' !!} ">
						@if(count($footer_text) > 0)
							@foreach ($footer_text as $key => $text1)
								<input name="text_footer[text][{{$key}}]" type="text" id="text_footer[text][[{{$key}}]" class="text_footer" value="{{ $text1 }}"  style="{!! $footer_input_style && isset($footer_input_style[$key-1]) ? $footer_input_style[$key-1] : '' !!}"  />
							@endforeach
						@else
							<input name="text_footer[text][1]" type="text" id="text_footer[text][1]" class="text_footer"  placeholder="Unesi text" />
						@endif
						<span class="add_line">Dodaj liniju</span>	
						<span class="add_link">Dodaj link</span>
						<span class="remove_line">Ukloni liniju</span>	
					</div>
				</section>
			</div>
		</main>
	</form>
	<script>
		$.getScript('/../js/mail_form.js');
	</script>
@stop