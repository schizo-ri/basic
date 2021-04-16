@php
	$path = 'storage/ads/' . $ad->id . '/';
	if(file_exists($path)) {
		$docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
	}	
@endphp
<div class="modal-header">
	
	<h3 class="panel-title">{{ $ad->subject }}<a class="btn color_grey btn_edit_ad" href="{{ route('ads.edit', $ad->id) }}"  title="{{ __('basic.edit')}}" rel="modal:open">
		<i class="fas fa-edit"></i>
	</a></h3>	
</div>
<div class="modal-body ad">	
	@if(isset($docs))
		@foreach($docs as $doc)
			@if(file_exists($path . $doc))
				<img src="{{ asset($path . $doc) }}" alt="Ad image"/>
			@endif
		@endforeach
	@endif
	<div class="panel-body">
		{!! $ad->description !!} 
	</div>
	<div class="panel-footer ad">
		<small>{{ $ad->employee->user['first_name'] .' | ' . \Carbon\Carbon::createFromTimeStamp(strtotime($ad->created_at))->diffForHumans()  }}</small>		
	</div>
</div>

