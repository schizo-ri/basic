@extends('Centaur::layout')

@section('title', __('basic.edit_ad'))

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">@lang('basic.edit_ad')</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('ads.update', $ad->id) }}">
					<div class="form-group {{ ($errors->has('category_id'))  ? 'has-error' : '' }}">
						<label>@lang('basic.ad_category')</label>
						<select  class="form-control" name="category_id">
							<option value="" disabled selected></option>
							@foreach ($categories as $category)
								<option value="{{ $category->id }}" {!! $ad->category_id == $category->id ? 'selected' : '' !!}>{{ $category->name }}</option>
							@endforeach
						</select>
						{!! ($errors->has('category_id') ? $errors->first('category_id', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('subject'))  ? 'has-error' : '' }}">
						<label>@lang('basic.subject')</label>
						<input name="subject" type="text" class="form-control" maxlength="150" value="{{ $ad->subject }}">
						{!! ($errors->has('subject') ? $errors->first('subject', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('description'))  ? 'has-error' : '' }}">
						<label>@lang('basic.description'):</label>
						<textarea id="summernote" name="description" maxlength="65535" >{!! $ad->description !!}</textarea>
						{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('price'))  ? 'has-error' : '' }}">
						<label>@lang('basic.price')</label>
						<input name="price" type="text" class="form-control" maxlength="100"  value="{{ $ad->price }}">
						{!! ($errors->has('price') ? $errors->first('price', '<p class="text-danger">:message</p>') : '') !!}
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