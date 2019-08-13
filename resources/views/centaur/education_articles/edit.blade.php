@extends('Centaur::layout')

@section('title', __('basic.edit_educationArticle'))

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('basic.edit_educationArticle')</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('education_articles.update', $educationArticle->id) }}">
					<div class="form-group {{ ($errors->has('theme_id'))  ? 'has-error' : '' }}">
						<label>@lang('basic.educationTheme')</label>
						<select  class="form-control"  name="theme_id" value="{{ $educationArticle->theme }}"  >
							<option value="" disabled selected></option>
							@foreach ($educationThemes as $educationTheme)
								<option value="{{ $educationTheme->id }}" {!! $educationArticle->theme_id == $educationTheme->id ? 'selected' : '' !!} >{{ $educationTheme->name }}</option>
							@endforeach
						</select>
						{!! ($errors->has('theme_id') ? $errors->first('theme_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('subject'))  ? 'has-error' : '' }}" >
						<label>@lang('basic.subject')</label>
						<input class="form-control"  name="subject" type="text" value="{{ $educationArticle->subject }}" />
						{!! ($errors->has('subject') ? $errors->first('subject', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('article'))  ? 'has-error' : '' }}">
						<label>@lang('basic.article'):</label>
						<textarea id="summernote" name="article"  >{!! $educationArticle->article !!}</textarea>
						{!! ($errors->has('article') ? $errors->first('article', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="padd_tb_20 form-group {{ ($errors->has('status'))  ? 'has-error' : '' }}">
						<label>Status</label>
						<input type="radio" class="" name="status" value="neaktivan" {!! $educationArticle->status == 'neaktivan' ? 'checked' : '' !!} />@lang('basic.inactive') 
						<input type="radio" class="" name="status" value="aktivan" {!! $educationArticle->status == 'aktivan' ? 'checked' : '' !!} />@lang('basic.active')
						{!! ($errors->has('status') ? $errors->first('status', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					{{ csrf_field() }}
					{{ method_field('PUT') }}
					<input class="btn-submit" type="submit" value="{{ __('basic.edit')}}" id="stil1">
				</form>
            </div>
        </div>
    </div>
</div>
<!-- Summernote -->
<link href="{{ URL::asset('node_modules/summernote/summernote-lite.css') }}" rel="stylesheet">
<script src="{{ URL::asset('node_modules/summernote/summernote-lite.min.js') }}" ></script>
<script>
$(document).ready(function() {
  $('#summernote').summernote();
});
</script>
@stop