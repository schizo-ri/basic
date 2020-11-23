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
							<label>Veličina<input name="{{ $element }}[font-size]" type="number" min="12" max="30" id="font-size[{{ $element }}]" class="font-size_{{ $element }}" value="14"/></label>
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
						div
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body">
						main
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="footer">
						footer
					</div>
				</section>
			</div>
		</main>
	</form>
	<script>
		var selected_element;
		var color;
		var font_size;
		var text_align;
		var border_color;
		var background_color;
		var border_width;
		var border_style;

		 $('#mail_template>div').on('click',function(){
			selected_element = $(this);
			var selected_id = $(selected_element).attr('id');
			console.log(selected_id);
			$('#mail_template>div').css('border','1px dashed #eee');
			$(selected_element).css('border','1px solid #ccc');
			$('#style_items>div').attr('hidden',true);
			var style_element = $('#style_items>div.'+selected_id);
			$(style_element).attr('hidden',false);
			
			$(style_element).find('.color_'+selected_id).on('input',function(){
				color = $( this ).val();
				console.log(color);
				$(selected_element).css('color', color ); 
			});
			$(style_element).find('.font-size_'+selected_id).on('change',function(){
				font_size = $( this ).val();
				$(selected_element).css('font-size',font_size +'px');
			});
			$(style_element).find('.text-align_'+selected_id).on('change',function(){
				text_align = $( this ).val();
				$(selected_element).css('text-align',text_align);
			});
			$(style_element).find('.border-color_'+selected_id).on('input',function(){
				border_color = $( this ).val();
				$(selected_element).css('border-color',border_color);
			});
			$(style_element).find('.border-width_'+selected_id).on('input',function(){
				border_width = $( this ).val();
				$(selected_element).css('border-width',border_width);
			});
			$(style_element).find('.border-style_'+selected_id).on('input',function(){
				border_style = $( this ).val();
				$(selected_element).css('border-style', border_style);
			});
			$(style_element).find('.background-color_'+selected_id).on('input',function(){
				background_color = $( this ).val();
				$(selected_element).css('background-color',background_color);
			});
			
		});
	</script>
@stop