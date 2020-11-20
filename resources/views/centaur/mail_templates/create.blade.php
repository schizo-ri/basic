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
					<div class="form-group mail_header header_style" >
						<h5>Header</h5>
						<p>Font</p>
						<label>Boja <input name="font_color[header]" type="color" id="font_color" value="#000000"/></label>
						<label>Veličina<input name="font_size[header]" type="number" min="12" max="30" id="font_size" value="16"/></label>
						<label>Poravnanje 
							<select name="font_align[header]" id="font_align">
								<option value="left">Lijevo</option>
								<option value="center" selected>Sredina</option>
								<option value="right">Desno</option>
							</select>
						</label>
						<p>Obrub</p>
						<label>Boja <input name="border_color[header]" type="color" id="border_color" value="#ffffff"/></label>
						<label>Debljina<input name="border_width[header]" type="number" min="0" max="4" id="border_width" value="0"/></label>
						<label>Stil
							<select name="border_style[header]" id="border_style">
								<option value="dotted">Točkasta</option>
								<option value="dashed" >Isprekidana</option>
								<option value="solid">Čvrsta</option> 
								<option value="double">Dvostruka</option> 
								<option value="none" selected>Bez</option> 
							</select>
						</label>
						<p>Pozadina</p>
						<label>Boja <input name="background_color[header]" type="color" id="background_color" value="#ffffff"/></label>
					</div>
					<div class="form-group main_style mail_main" >
						<h5>Body</h5>
						<p>Font</p>
						<label>Boja <input name="font_color[main]" type="color" id="font_color" value="#000000"/></label>
						<label>Veličina <input name="font_size[main]" type="number" min="12" max="30" id="font_size" value="14" /></label>
						<label>Poravnanje 
							<select name="font_align[main]" id="font_align">
								<option value="left">Lijevo</option>
								<option value="center" selected >Sredina</option>
								<option value="right">Desno</option>
							</select>
						</label>
						<p>Obrub</p>
						<label>Boja <input name="border_color[main]" type="color" id="border_color" value="#ffffff"/></label>
						<label>Debljina<input name="border_width[main]" type="number" min="0" max="4" id="border_width" value="0"/></label>
						<label>Stil
							<select name="border_style[main]" id="border_style">
								<option value="dotted">Točkasta</option>
								<option value="dashed" >Isprekidana</option>
								<option value="solid">Čvrsta</option> 
								<option value="double">Dvostruka</option> 
								<option value="none" selected>Bez</option> 
							</select>
						</label>
						<p>Pozadina</p>
						<label>Boja <input name="background_color[main]" type="color" id="background_color" value="#ffffff"/></label>
					</div>
					<div class="form-group footer_style mail_footer" >
						<h5>Footer</h5>
						<p>Font</p>
						<label>Boja <input name="font_color[footer]" type="color" id="font_color" value="#000000"/></label>
						<label>Veličina <input name="font_size[footer]" type="number" min="12" max="30" id="font_size"  value="12" /></label>
						<label>Poravnanje 
							<select name="font_align[footer]" id="font_align">
								<option value="left">Lijevo</option>
								<option value="center" selected>Sredina</option>
								<option value="right">Desno</option>
							</select>
						</label>
						<p>Obrub</p>
						<label>Boja <input name="border_color[footer]" type="color" id="border_color" value="#ffffff"/></label>
						<label>Debljina<input name="border_width[footer]" type="number" min="0" max="4" id="border_width" value="0"/></label>
						<label>Stil
							<select name="border_style[footer]" id="border_style">
								<option value="dotted">Točkasta</option>
								<option value="dashed" >Isprekidana</option>
								<option value="solid">Čvrsta</option> 
								<option value="double">Dvostruka</option> 
								<option value="none" selected>Bez</option> 
							</select>
						</label>
						<p>Pozadina</p>
						<label>Boja <input name="background_color[footer]" type="color" id="background_color" value="#ffffff"/></label>
					</div>
				</section>
				<section class="col-xs-6 col-sm-6 col-md-6 col-lg-6 float_left" id="mail_template">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="mail_header">
						div
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="mail_main">
						main
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="mail_footer">
						footer
					</div>
				</section>
			</div>
		</main>
	</form>
	<script>
		var selected_element;
		var font_color;
		var font_size;
		var font_align;
		var border_color;
		var background_color;
		 $('#mail_template>div').on('click',function(){
			selected_element = $(this);
			var selected_id = $(selected_element).attr('id');
			console.log(selected_id);
			$('#mail_template>div').css('border','1px dashed #eee');
			$(selected_element).css('border','1px solid #ccc');
		/* 	$('#style_items>div').attr('hidden',true); */
			var style_element = $('#style_items>div.'+selected_id);
		/* 	
			$(style_element).attr('hidden',false); */

			$(style_element).find('#font_color').on('input',function(){
				font_color = $( this ).val();
				console.log(font_color);
				$(selected_element).css('color', font_color ); 
			});
			$(style_element).find('#font_size').on('change',function(){
				font_size = $( this ).val();
				$(selected_element).css('font-size',font_size +'px');
			});
			$(style_element).find('#font_align').on('change',function(){
				font_align = $( this ).val();
				$(selected_element).css('text-align',font_align);
			});
			$(style_element).find('#border_color').on('input',function(){
				border_color = $( this ).val();
				$(selected_element).css('border-color',border_color);
			});
			$(style_element).find('#border_width').on('input',function(){
				border_width = $( this ).val();
				$(selected_element).css('border-width',border_width);
			});
			$(style_element).find('#border_style').on('input',function(){
				border_style = $( this ).val();
				$(selected_element).css('border-style', border_style);
			});
			$(style_element).find('#background_color').on('input',function(){
				background_color = $( this ).val();
				$(selected_element).css('background-color',background_color);
			});
			
		});
	</script>
@stop