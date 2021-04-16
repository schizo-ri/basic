<div class="modal-header">
	<h3 class="panel-title">Radne kategorije elektroradova na projektu</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('project_work_tasks.store') }}">
		<fieldset>
			@if (isset($project) &&  $project )
				<p>Projekt: {{ $project->erp_id . ' ' . $project->name }}</p>
				<input type="hidden" name="project_id" value="{{ $project->id }}">
			@else
				<div class="form-group {{ ($errors->has('project_id')) ? 'has-error' : '' }}">
					<label>Projekt</label>
					<select class="form-control" name="project_id" value="{{ old('project_id') }}" >
						<option value="" selected disabled></option>
						@foreach ($projects as $projects)
							<option value="{{ $project->id }}">{{ $project->name }}</option>
						@endforeach	
					</select>
					{!! ($errors->has('project_id') ? $errors->first('project_id', '<p class="text-danger">:message</p>') : '') !!}
				</div>
			@endif
			<div class="form-group {{ ($errors->has('task_id')) ? 'has-error' : '' }}">
				<label>Kategorije rada</label>
					@foreach ($workTasks as $workTask)
						<p class="overflow_hidd">
							<span class="float_left padd_r_20 col-8 line_height_45">{{ $workTask->name }} </span>
							<input type="number" step="0.01" name="task_id[{{ $workTask->id }}]" class="form-control float_left col-4 hours" value="{{ old('task_id') }}" />
						</p>
					@endforeach	
				{!! ($errors->has('task_id') ? $errors->first('task_id', '<p class="text-danger">:message</p>') : '') !!}
				<p class="red">Ukupno sati: <span class="total">0</span></p>
			</div>
			@csrf
			<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>
<script>
	var total = 0;
	console.log( total );
	$('.hours').on('focusout',function(){
		total = 0;
		$( ".hours" ).each(function( index ) {
			if( $.isNumeric($(this).val() )) {
				total = total + parseFloat($(this).val());
			}
		});
		$('.total').text(total);
	});
</script>