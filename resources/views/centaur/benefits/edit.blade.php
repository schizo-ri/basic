<span hidden class="locale" >{{ App::getLocale() }}</span>
<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_benefit')</h3>
</div>
<div class="modal-body">
	<form class="form_benefit" accept-charset="UTF-8" role="form" method="post" action="{{ route('benefits.update', $benefit->id ) }}" enctype="multipart/form-data">
		<fieldset>
			<?php
                $path = 'img/benefits/' . $benefit->id . '/';
                if(file_exists($path)) {
                    $docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
                }
            ?>
			<div class="form-group">
				<label class="label_file" for="file">@lang('basic.add_image')<span><img src="{{ URL::asset('icons/download.png') }}" />Add image here</span></label>
				<input type='file' id="file" name="fileToUpload" >
				<span id="file_name"></span>
				@if(isset($docs))
					@if(file_exists($path . end($docs)) && end($docs)!= '' )
						<span class="ad_image">{{ end($docs) }} <a class="action_confirm danger" href="{{ action('DocumentController@imageDelete', ['image' => $path . end($docs)]  ) }}" data-method="delete" data-token="{{ csrf_token() }}"><i class="far fa-trash-alt"></i></a> </span>
					@endif
				@endif
			</div>
			<div class="form-group {{ ($errors->has('title'))  ? 'has-error' : '' }}">
				<label>@lang('basic.title')</label>
				<input name="title" type="text" class="form-control" maxlength="255" value="{{ $benefit->title }}" required >
				{!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
			</div>			
			<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
				<label>@lang('basic.description')</label>
				<textarea name="description" id="mytextarea"  maxlength="16777215"  >{{ $benefit->description }}</textarea>
				{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('contact'))  ? 'has-error' : '' }}">
				<label>@lang('basic.contact_person')</label>
				<input name="contact" type="text" class="form-control" maxlength="100" value="{{ $benefit->contact }}"  >
				{!! ($errors->has('url') ? $errors->first('contact', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('email'))  ? 'has-error' : '' }}">
				<label>E-mail</label>
				<input name="email" type="text" class="form-control" maxlength="100" value="{{ $benefit->email }}"  >
				{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('phone'))  ? 'has-error' : '' }}">
				<label>@lang('basic.phone')</label>
				<input name="phone" type="text" class="form-control" maxlength="100" value="{{ $benefit->phone }}"  >
				{!! ($errors->has('phone') ? $errors->first('phone', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group">
				<label>Status</label>
				<input type="radio" id="status_0" name="status" value="0" {!! $benefit->status == 0 || $benefit->status == null ? 'checked' : '' !!} /><label for="status_0">@lang('basic.inactive')</label>
				<input type="radio" id="status_1" name="status" value="1"  {!! $benefit->status == 1 ? 'checked' : '' !!}/><label for="status_1">@lang('basic.active')</label>
			</div>
			{{ csrf_field() }}
			{{ method_field('PUT') }}
			<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>
<script>
	/* $.getScript( '/../js/validate.js'); */
	$.getScript( '/../js/tinymce.js');
	$('body').on($.modal.CLOSE, function(event, modal) {
		$.getScript('/../node_modules/tinymce/tinymce.min.js');
	});
	$('#file').change(function(){
        $('#file_name').text( $('input[type=file]').val());
	});
</script>
