@extends('Centaur::layout')

@section('title', __('basic.documents'))

@section('content')
<div class="row">
    <div class="page-header">
        <h1>@lang('basic.documents')</h1>
		@if(Sentinel::getUser()->hasAccess(['documents.create']) || in_array('documents.create', $permission_dep))
			<div class="upload">
				<h3>Upload</h3>
				<form action="{{ route('documents.store') }}" method="post" enctype="multipart/form-data" style="text-align:left;">
					<div class="form-group ">
						<label class="padd_10">Za djelatnika </label>
						<select class="djelatnik" name="employee_id" value="{{ old('employee_id') }}" required>
							<option selected="selected" value=""></option>
							<option name="svi" value="svi">Svi zaposlenici</option>
							@foreach($employees as $employee)
								<option name="employee_id" value="{{ $employee->id }}">{{ $employee->user['last_name']. ' ' . $employee->user['first_name'] }}</option>
							@endforeach	
						</select>
					</div>
					<div class="form-group ">Izaberi dokument 
						<input type="file" name="fileToUpload" required>
						{{ csrf_field() }}
					</div>
					<div class="form-group ">
					<input type="submit" value="Upload Image" name="submit">
					</div>
				</form>
			</div>
		@endif
    </div>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($documents))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.title')</th>
							<th>@lang('basic.employee')</th>
							<th>@lang('basic.path')</th>
							<th>@lang('basic.date')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($documents as $document)
							<?php  
								$open = $document->path . $document->title;
							?>
							<tr>
								<td><a href="{{ asset($open) }}" target="_blank">{{ $document->title }}</a></td>
								<td>{{ $document->employee->user['first_name'] . ' ' .  $document->employee->user['last_name'] }}</td>
								<td>{{ $document->path }}</td>
								<td>{{ $document->created_at }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
				@lang('basic.no_data')
			@endif
		</div>
	</div>
</div>
<!-- Datatables -->
<script type="text/javascript" src="{{ URL::asset('dataTables/datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/JSZip-2.5.0/jszip.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/pdfmake-0.1.36/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/pdfmake-0.1.36/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('dataTables/Buttons-1.5.6/js/buttons.print.min.js') }}"></script>

<script src="{{ URL::asset('js/datatables.js') }}"></script>
@stop