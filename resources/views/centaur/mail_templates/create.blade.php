@extends('Centaur::admin')

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
							<textarea class="form-control" name="description" type="text" value="{{ old('description') }}" placeholder="{{ __('basic.description') }}" required ></textarea>
							{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group {{ ($errors->has('for_mail')) ? 'has-error' : '' }}">
							<select class="form-control" name="for_mail" value="{{ old('for_mail') }}" required >
								<option selected disabled ></option>
								@foreach ($docs as $doc)
									<option value="{{ strstr($doc, '.', true) }}" >{{ strstr($doc, '.', true) }}</option>
								@endforeach
							</select>
						</div>
					</section>
					@foreach ($elements as $element)
						<div class="form-group {{ $element }}" hidden>
							<h5>{{ ucfirst( $element) }}</h5>
							<p>Font</p>
							<label>Boja <input name="{{ $element }}[color]" type="color" id="color[{{ $element }}]" class="color_{{ $element }}" value="#000000"/></label>
							<label>Veličina<input name="{{ $element }}[font-size]" type="number" min="8" max="30" id="font-size[{{ $element }}]" class="font-size_{{ $element }}" value="12"/></label>
							<label>Poravnanje 
								<select name="{{ $element }}[text-align]" id="text-align[{{ $element }}]" class="text-align_{{ $element }}" >
									<option value="left" selected>Lijevo</option>
									<option value="center" >Sredina</option>
									<option value="right">Desno</option>
								</select>
							</label>
							<p>Obrub</p>
							<label>Boja <input name="{{ $element }}[border-color]" type="color" id="border-color[{{ $element }}]" class="border-color_{{ $element }}" value="#ffffff"/></label>
							<label>Debljina<input name="{{ $element }}[border-width]" type="number" min="0" max="4" id="border-width" class="border-width_{{ $element }}" value="0"/></label>
							<label>Stil
								<select name="{{ $element }}[border-style]" id="border-style[{{ $element }}]" class="border-style_{{ $element }}">
									<option value="dotted">Točkasta</option>
									<option value="dashed" >Isprekidana</option>
									<option value="solid">Čvrsta</option> 
									<option value="double">Dvostruka</option> 
									<option value="none" selected>Bez</option> 
								</select>
							</label>
							<p>Pozadina</p>
							<label>Boja <input name="{{ $element }}[background-color]" type="color" id="background-color[{{ $element }}]" class="background-color_{{ $element }}" value="#ffffff"/></label>
						</div>
					@endforeach
				</section>
				<section class="col-xs-6 col-sm-6 col-md-6 col-lg-6 float_left" id="mail_template">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="header">
						Lorem Ipsum
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body">
						What is Lorem Ipsum?
						Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="footer">
					"Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit..."
					</div>
				</section>
			</div>
		</main>
	</form>
	<script>
		$.getScript('/../js/mail_form.js');
	</script>
@stop