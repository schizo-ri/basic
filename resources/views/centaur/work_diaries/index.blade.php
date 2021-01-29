@extends('Centaur::admin')

@section('title', __('basic.work_diary'))

@section('content')
	<header class="page-header diary_header">
		<div class="index_table_filter">
			<label>
				<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			@if(Sentinel::getUser()->hasAccess(['work_diaries.create']) || in_array('work_diaries.create', $permission_dep))
				<a class="btn-new add_new" href="{{ route('work_diaries.create') }}" title="{{ __('basic.add_work_task')}}"  rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif
			<div class="div_select2">
				<select id="filter_month" class="select_filter filter_month" >
					<option value="all">@lang('basic.all_month')</option>
					@foreach ($dates as $date)
						<option value="{{ $date }}" {!! $date == date('Y-m') ? 'selected' : '' !!}>{{ $date }}</option>
					@endforeach
				</select>
			</div>
			<div class="div_select2">
				<select id="filter_tasks" class="select_filter filter_tasks" >
					<option value="all">@lang('basic.view_all')</option>
					@foreach ($workTasks as $key => $workTask)
						<option value="{{ $key }}">{{  $workTask }}</option>
					@endforeach
				</select>
			</div>
			<div class="div_select2">
				<select id="filter_employees" class="select_filter filter_employees" >
					<option value="all" selected >SVI djelatnici</option>
					@foreach ($employees as $employee)
						<option value="{{ $employee->id }}" >{{ $employee->last_name . ' ' .$employee->first_name }}</option>
					@endforeach
				</select>
			</div>
			<div class="div_select2">
				<select id="filter_project" class="select_filter filter_project" >
					<option value="all" selected >SVI projekti</option>
					@foreach ($projects as $key => $project)
						<option value="{{  $key  }}" >{{  $project }}</option>
					@endforeach
				</select>
			</div>
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="table-responsive">
			@if(count($workDiaries))
				<table id="index_table" class="display table table-hover">
					<thead>
						<tr>
							<th>@lang('basic.employee')</th>
							<th>@lang('basic.date')</th>
							<th>@lang('basic.project')</th>
							<th>@lang('basic.tasks')</th>
							<th>@lang('basic.time')</th>
							<th>@lang('basic.description')</th>
							<th class="not-export-column">@lang('basic.options')</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($workDiaries as $workDiary)
							@foreach ($workDiary->hasWorkDiaryItem as $item)
								<tr>
									<td>{{ $workDiary->employee->user->last_name . ' ' . $workDiary->employee->user->first_name }}</td>
									<td>{{ date('d.m.Y', strtotime($workDiary->date)) }}</td>
									<td>{!! $workDiary->project ? $workDiary->project->name : '' !!}</td>
									<td>{{ $item->workTask->name }}</td>
									<td>{{ date('H:i', strtotime($item->time) ) }}</td>
									<td>{{ $item->description }}</td>
									<td class="center">
										@if(Sentinel::getUser()->hasAccess(['work_diaries.update']) || in_array('work_diaries.update', $permission_dep))
											<a href="{{ route('work_diaries.edit', $workDiary->id) }}" class="btn-edit" title="{{ __('basic.edit_work_task')}}" rel="modal:open">
												<i class="far fa-edit"></i>
											</a>
										@endif
										@if(  Sentinel::getUser()->hasAccess(['work_diaries.delete']) || in_array('work_diaries.delete', $permission_dep) )
											<a href="{{ route('work_diary_items.destroy', $item->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
												<i class="far fa-trash-alt"></i>
											</a>
										@endif
									</td>
								</tr>
							@endforeach
						@endforeach
					</tbody>
					<tfoot>
						<tr>
							<td colspan="7" >Ukupan broj sati {{ $sum_time }}</td>
						</tr>
					</tfoot>
				</table>
			@else
				<p class="no_data">@lang('basic.no_data')</p>
			@endif
		</div>
	</main>
@stop