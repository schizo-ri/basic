@extends('Centaur::layout')

@section('title', __('basic.add_permissions'))

@section('content')
<div class="row">
	@if(isset($ad))
		<div class="col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">{{ $ad->subject }}</h3>
				</div>
				<div class="panel-body">
				{!! $ad->description !!}
				</div>
				 <div class="panel-footer ad">
					<small>{{ $ad->employee->user['first_name'] .' | ' . \Carbon\Carbon::createFromTimeStamp(strtotime($ad->created_at))->diffForHumans()  }}</small>
				 </div>
			</div>
		</div>
	@endif
</div>
@stop
