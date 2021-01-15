@extends('Centaur::admin')
@php
	use App\Models\MailTemplate;
@endphp
@section('title', __('basic.add_mail_templates'))
	@section('content')
	<form accept-charset="UTF-8" role="form" method="post" class="mail_form" action="{{ route('mail_templates.store') }}" >
		<header class="page-header">
			<p class="mail_tamplate_title">@lang('basic.add_mail_templates'){{ csrf_field() }}
				<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
			</p>
		</header>
		<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="table-responsive">
				<section class="col-xs-6 col-sm-6 col-md-6 col-lg-6 float_left" id="style_items">
					<section>
						<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
							<input class="form-control" name="name" type="text" value="{{ old('name') }}" placeholder="{{ __('basic.name') }}" required />
							{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
							<textarea class="form-control" name="description" type="text" value="{{ old('description') }}" placeholder="{{ __('basic.description') }}" rows="3" required ></textarea>
							{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('for_mail')) ? 'has-error' : '' }}">
							<select class="form-control" name="for_mail" value="{{ old('for_mail') }}" required >
								<option selected disabled ></option>
								@foreach ($docs as $doc)
									@if( !  MailTemplate::where('for_mail',  $doc)->first() )
										<option value="{{ strstr($doc, '.', true) }}" >{{ strstr($doc, '.', true) }}</option>
									@endif
								@endforeach
							</select>
						</div>
					</section>
					@foreach ($elements as $element)
						@include('Centaur::mail_templates.create_style', ['element' => $element ])
						@foreach ($child_elements as $key => $child_element)
							@include('Centaur::mail_templates.create_style', ['element' => $element . '_'. $child_element, 'count_input' => 0 ])
						@endforeach
					@endforeach
				</section>
				<section class="col-xs-6 col-sm-6 col-md-6 col-lg-6 float_left" id="mail_template">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="header">
						<input name="text_header[text][1]" type="text" id="text_header[text][1]" class="text_header" placeholder="Unesi naslov"/>
						<span class="add_line">Dodaj liniju</span>	
						<span class="add_link">Dodaj link</span>
						<span class="remove_line">Ukloni liniju</span>	
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body">
						<input name="text_body[text][1]" type="text" id="text_body[text][1]" class="text_body" placeholder="Unesi tekst"/>
						<span class="add_line">Dodaj liniju</span>	
						<span class="add_link">Dodaj link</span>
						<span class="remove_line">Ukloni liniju</span>	

					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="footer">
						<input name="text_footer[text][1]" type="text" id="text_footer[text][1]" class="text_footer" placeholder="Unesi tekst" />
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