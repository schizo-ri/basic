<span hidden class="locale" >{{ App::getLocale() }}</span>
<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_benefit')</h3>
</div>
<div class="modal-body">
	<form class="form_benefit" accept-charset="UTF-8" role="form" method="post" action="{{ route('benefits.store') }}" enctype="multipart/form-data" >
		<fieldset>
			<div class="form-group">
				<label class="label_file" for="file">@lang('basic.add_image')<span><img src="{{ URL::asset('icons/download.png') }}" />Add image here</span></label>
				<input type='file' id="file" name="fileToUpload" >
				<span id="file_name"></span>
			</div>
			<div class="form-group {{ ($errors->has('title'))  ? 'has-error' : '' }}">
				<label>@lang('basic.title')</label>
				<input name="title" type="text" class="form-control" maxlength="255" value="{{ old('title') }}" required >
				{!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
			</div>			
			<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
				<label>@lang('basic.description')</label>
				<textarea name="description" id="mytextarea"  maxlength="16777215"  >{{ old('description') }}</textarea>
				{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('contact'))  ? 'has-error' : '' }}">
				<label>@lang('basic.contact_person')</label>
				<input name="contact" type="text" class="form-control" maxlength="100" value="{{ old('url') }}"  >
				{!! ($errors->has('url') ? $errors->first('url', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('email'))  ? 'has-error' : '' }}">
				<label>E-mail</label>
				<input name="email" type="text" class="form-control" maxlength="100" value="{{ old('email') }}"  >
				{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('phone'))  ? 'has-error' : '' }}">
				<label>@lang('basic.phone')</label>
				<input name="phone" type="text" class="form-control" maxlength="100" value="{{ old('phone') }}"  >
				{!! ($errors->has('phone') ? $errors->first('phone', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group">
				<label>Status</label>
				<input type="radio" id="status_0" name="status" value="0" checked /><label for="status_0">@lang('basic.inactive')</label>
				<input type="radio" id="status_1" name="status" value="1" /><label for="status_1">@lang('basic.active')</label>
			</div>
			{{ csrf_field() }}
			<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
			<a href="" class="modal_close float_r" rel="modal:close">@lang('basic.cancel')</a>
		</fieldset>
	</form>
</div>
<script>
	//$.getScript( '/../js/validate.js');
	tinymce.init({
    selector: '#mytextarea',
    height : 300,	
    plugins: "image",
    menubar: 'file edit insert view format table tools help',
    toolbar: [
        {
        name: 'history', items: [ 'undo', 'redo' ]
        },
        {
        name: 'formatting', items: [ 'bold', 'italic', 'forecolor', 'backcolor' ]
        },
        {
        name: 'alignment', items: [ 'alignleft', 'aligncenter', 'alignright', 'alignjustify' ]
        },
        {
        name: 'indentation', items: [ 'outdent', 'indent' ]
        },
        {
        name: 'image', items: [ 'image','url' ]
        },
        {
        name: 'styles', items: [ 'styleselect' ]
        },
    ],

    image_list: [
        {title: 'My image 1', value: 'https://www.example.com/my1.gif'},
        {title: 'My image 2', value: 'http://www.moxiecode.com/my2.gif'}
    ]		
});
	$('body').on($.modal.CLOSE, function(event, modal) {
		$.getScript('/../node_modules/tinymce/tinymce.min.js');
	});
	$('#file').change(function(){
        $('#file_name').text( $('input[type=file]').val());
	});
</script>