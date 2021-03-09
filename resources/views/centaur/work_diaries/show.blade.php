@extends('Centaur::layout')

@section('title', __('basic.work_diary'))

@section('content')
<div class="index_page diary_table">
	<main class="col-md-12 index_main main_documents float_right">
		<section>
			<div class="page-header header_document">
				<a class="link_back" href="{{ url()->previous() }}" ><span class="curve_arrow_left"></span></a>
				@lang('basic.work_diary')
			</div>
			<main class="all_documents">
				<div class="table-responsive">
					<header class="page-header diary_header">
						<div class="index_table_filter">
							<label>
								<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearch()" id="mySearch">
							</label>
							@if(Sentinel::getUser()->hasAccess(["work_diaries.create"]) || in_array("work_diaries.create", $permission_dep) )
								<a class="add_new" href="{{ route('work_diaries.create') }}" class="" rel="modal:open">
									<i class="fas fa-plus"></i>
								</a>
							@endif
							@if( Sentinel::inRole('administrator') || count( $projects->where('employee_id', Sentinel::getUser()->employee->id ) )>0 || count( $projects->where('employee_id2', Sentinel::getUser()->employee->id ) )>0)
								<div class="div_select2">
									<select id="filter_month" class="select_filter filter_month" >
										<option value="all">@lang('basic.all_month')</option>
										@foreach ($dates as $date)
											<option value="{{ $date }}" {!! $date == date('Y-m') ? 'selected' : '' !!}>{{ $date }}</option>
										@endforeach
									</select>
								</div>
							@endif
							{{-- <div class="div_select2">
								<select id="filter_tasks" class="select_filter filter_tasks" >
									<option value="all">@lang('basic.view_all')</option>
									@foreach ($workTasks as $key => $workTask)
										<option value="{{ $key }}">{{ $workTask }}</option>
									@endforeach
								</select>
							</div> --}}
						{{-- @if( Sentinel::inRole('administrator'))
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
									@foreach ($projects as $project)
										<option value="{{ $project->id }}" >{{ '['.$project->erp_id . '] '. $project->name }}</option>
									@endforeach
								</select>
							</div>
							@endif --}}
						</div>
					</header>
					<section class="page-main">
						@if(count($workDiaries))
							@if ( Sentinel::inRole('administrator') || count( $projects->where('employee_id', Sentinel::getUser()->employee->id ) )>0 || count( $projects->where('employee_id2', Sentinel::getUser()->employee->id ) )>0)
								@foreach ($projects as $project)
									@if(count( $workDiaries->where('project_id',$project->id ))> 0)
										<section class="diary_project panel">
											@php
												$workDiariesProject = $workDiaries->where('project_id',$project->id );
												$dates = $workDiariesProject->unique('date')->sortByDesc('date')->pluck('date');
												
												$all_time_project = 0;
											@endphp
											<p class="font_white bg_darkblue padd_10 margin_0 collapsible cursor" >{!! $project->erp_id ? '['. $project->erp_id . '] ' : '' !!} {{ $project->name }}</p>
											@foreach ($dates as $date)
												<section class="project_table">
													@php
														$total_seconds = 0;
													@endphp
													<p class="font_white bg_darkblue padd_10 margin_0 opacity_04 margin_0 collapsible cursor" >{{ $date }}</p>
													<table class="display table table-hover ">
														<thead>
															<tr>
																<th class="align_l">Djelatnik</th>
																@foreach ($workTasks as $key => $workTask)
																	<th>{{ $workTask }}</th>
																@endforeach
																<th>Ukupno vrijeme</th>
															</tr>
														</thead>
														<tbody>
															@foreach ( $workDiariesProject->where('date', $date) as $workDiary )
															@php
																$seconds = 0;
															@endphp
																<tr>
																	<td>{{ $workDiary->employee->user->first_name . ' ' .  $workDiary->employee->user->last_name }}</td>
																	@foreach ($workTasks as $key => $workTask)
																		@php
																			$item = $workDiary->hasWorkDiaryItem->where('task_id', $key )->first();
																			if($item) {
																				list($hour,$minute) = explode(':', $item->time);
																				$seconds += $hour*3600;
																				$seconds += $minute*60;																	
																			}
																		@endphp
																		<td class="align_c">
																			{!! $item ? date('H:i', strtotime($item->time)) : ''!!}
																			<br>
																			{!! $item ? $item->description : ''!!}
																		</td>
																	@endforeach
																	<td  class="align_c">{{  $seconds/3600 }}</td>
																	@php
																		$total_seconds += $seconds;
																	@endphp
																</tr>
															@endforeach
														</tbody>
														<tfoot>
															<tr>
																<td  colspan="10" class="align_r">Ukupno vrijeme</td>
																<td colspan="1" class="align_c">{{ $total_seconds / 3600 .' h' }}</td>
															</tr>
														</tfoot>										
													</table>
												</section>
												@php
													$all_time_project += $total_seconds;
												@endphp
											@endforeach
											<p class="hidden">Ukupno vrijeme projekta: {{ $all_time_project  / 3600 .' h' }}</p>
										</section>
									@endif
								@endforeach
							@else 
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
													<td>{!! $workDiary->project ?  $workDiary->project->erp_id . ' ' . $workDiary->project->name : '' !!}</td>
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
							@endif
						@else
							<div class="placeholder">
								<img class="" src="{{ URL::asset('icons/placeholder_document.png') }}" alt="Placeholder image" />
								<p> @lang('basic.no_file1')
									@if(Sentinel::getUser()->hasAccess(["documents.create"]) || in_array("documents.create", $permission_dep) )
									@lang('basic.no_file2')
										<label type="text" class="add_new" rel="modal:open" >
											<i style="font-size:11px" class="fa">&#xf067;</i>
										</label>
										@lang('basic.no_file3')
									@endif
								</p>
							</div>
						@endif
					</section>
				</div>
			</main>
		</section>
	</main>
</div>
@stop