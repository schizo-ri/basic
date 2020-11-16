<div class="modal-header">
	<h3 class="panel-title">@lang('basic.edit_instruction')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('instructions.update', $instruction->id) }}">
		<div class="form-group {{ ($errors->has('department_id')) ? 'has-error' : '' }}" >
			<label>@lang('basic.department')</label>
			<select class="form-control" name="department_id" required multiple>
				@foreach($departments as $department)
					<option value="{{ $department->id }}" {!! $instruction->department_id == $department->id ? 'selected' : '' !!} >{{ $department->name }}</option>
				@endforeach
			</select>
			{!! ($errors->has('department_id') ? $errors->first('department_id', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
			<label>@lang('basic.title')</label>
			<input class="form-control" placeholder="{{ __('basic.title')}}" name="title" type="text" maxlength="191" value="{{ $instruction->title }}" required />
			{!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('description')) ? 'has-error' : '' }}">
			<label>@lang('basic.description')</label>
			<textarea name="description" id="tinymce_textarea" maxlength="16777215"  >{{ $instruction->description }}</textarea>
			{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="active_status form-group">
			<label for="">Status</label>
			<label class="float_l container_radio status_checked"> @lang('basic.active')
				<input type="radio" name="active" value="1" {!! $instruction->active == 1 ? 'checked' : '' !!} />
				<span class="checkmark active"></span>
			</label>
			<label class="float_l container_radio status_checked ">@lang('basic.inactive')
				<input type="radio" name="active" value="0" {!! $instruction->active == 0 ? 'checked' : '' !!}/>
				<span class="checkmark inactive"></span>
			</label>
		</div>
		{{ csrf_field() }}
		{{ method_field('PUT') }}
		<input class="btn-submit" type="submit" value="{{ __('basic.edit')}}">
	</form>
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
/* 	$.getScript( '/../js/validate.js'); */
	if( $('#tinymce_textarea').length >0 ) {
		tinymce.init({
			selector: '#tinymce_textarea',
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
	}
</script>