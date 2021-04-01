@extends('Centaur::layout')

@section('title', __('basic.annual_goals'))
<link href="{{ URL::asset('/../select2-develop/dist/css/select2.min.css') }}" />

@section('content')
<div class="index_page index_documents">
	<main class="col-12 index_main main_documents float_right">
		<section>
			<div class="page-header header_document">
				<a class="link_back" href="{{ url()->previous() }}" ><span class="curve_arrow_left"></span></a>
				OKR
				@if (Sentinel::getUser()->hasAccess(['annual_goals.view']) 	)
					<a href="{{ route('annual_goals.index') }}" class="view_all" title="{{ __('basic.annual_goals') }}" >@lang('basic.annual_goals')</a>
				@endif
			</div>
			<main class="all_documents all_okrs">
				<div class="">
					<header class="page-header">
						<div class="index_table_filter">
							<label>
								<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchOkr()" id="mySearchOkr">
							</label>
							<div class="div_select2">
								<select id="filter_quarter" class="select_filter sort" >
									<option  value="all" selected>Svi kvartali</option>
									@foreach ($quarters as $quarter)
										<option value="{{ $quarter }}" {!! $this_quarter ==  $quarter ? 'selected' : '' !!}>{{ $quarter }}</option>
									@endforeach
								</select>
							</div>
							<div class="div_select2">
								<select id="filter_status" class="select_filter sort" >
									<option  value="all" selected>Svi</option>
									<option value="finished" >{{ __('basic.finished') }}</option>
									<option value="unfinished" >{{ __('basic.unfinished') }}</option>
								</select>
							</div>
							@if (Sentinel::inRole('administrator'))
								<div class="div_select2">
									<select id="filter_okr_empl" class="select_filter sort" >
										<option value="all" selected>Svi djelatnici</option>
										@foreach ($employees as $empl)
											@if ($empl)
												<option value="{{  $empl->user->first_name . ' ' . $empl->user->last_name  }}" >{{ $empl->user->first_name . ' ' . $empl->user->last_name }}</option>
											@endif
										@endforeach
									</select>
								</div>
							@endif							
						</div>
					</header>
					<section class="okrs">
						<div class="tab_okr">
							<button class="tablinks active" onclick="openOKR(event, 'companyOkr')">Duplico OKR</button>
							<button class="tablinks " onclick="openOKR(event, 'myOkr')">Moj OKR</button>
							<button class="tablinks" onclick="openOKR(event, 'myResults')">Moji ključni rezultati</button>
							<button class="tablinks" onclick="openOKR(event, 'myTasks')">Moj zadaci</button>
						</div>
						<div id="companyOkr" class="tabcontent">
							<section>
								<div class="goals">
									<h4>Godišnji ciljevi
										@if(Sentinel::getUser()->hasAccess(['annual_goals.create']) || in_array('annual_goals.view', $permission_dep))
											<a class="add_new" href="{{ route('annual_goals.create') }}" rel="modal:open">
												<i class="fas fa-plus"></i>
											</a>
										@endif
									</h4>
									@foreach ($annualGoals as $key => $goal)
										<p >{{ ($key + 1) . '. ' . $goal->name}} 
											@if(Sentinel::getUser()->hasAccess(['annual_goals.update']) || in_array('annual_goals.update', $permission_dep))
												<a href="{{ route('annual_goals.edit', $goal->id) }}" class="btn-edit" rel="modal:open">
														<i class="far fa-edit"></i>
												</a>
											@endif
											@if( Sentinel::getUser()->hasAccess(['annual_goals.delete']) || in_array('annual_goals.delete', $permission_dep))
												<a href="{{ route('annual_goals.destroy', $goal->id) }}" class="action_confirm btn-delete danger" data-token="{{ csrf_token() }}" >
													<i class="far fa-trash-alt"></i>
												</a>
											@endif
										</p>
									@endforeach
								</div>
								<h4>Duplico OKR
									@if(Sentinel::getUser()->hasAccess(['okrs.create']) || in_array('okrs.view', $permission_dep))
										<a class="add_new" href="{{ route('okrs.create') }}" rel="modal:open" title="{{ __('basic.add_okr')}}">
											<i class="fas fa-plus"></i>
										</a>
									@endif
								</h4>
								@if(count( $okrs ) > 0)
									<section class="col-12 overflow_hidd section_okr">
										<div class="col-12 overflow_hidd div_header">
											<div class="col-sm-8 col-4 float_l">@lang('basic.name')</div>
											<div class="col-sm-4 col-1 float_l">@lang('basic.employee')</div>
											<div class="col-sm-3 col-1 float_l capitalize">@lang('basic.quarter')</div>
											<div class="col-sm-3 col-1 float_l capitalize">@lang('absence.end_date')</div>
											<div class="col-sm-6 col-2 float_l">@lang('basic.progress') <i class="fas fa-pencil-alt"></i></div>
											<div class="col-sm-8 col-2 float_l">@lang('basic.comment')</div>
											<div class="col-sm-4 col-1 float_l">@lang('basic.options')</div>
										</div>
										<div class="col-12 overflow_hidd div_main">
											@foreach ( $okrs as $okr )
												@if ($okr->status == 0 || ( $okr->status == 1 && ( Sentinel::inRole('uprava') || Sentinel::getUser()->employee->id == $okr->employee_id) ) )
													<div class="okr_group"  id="okrgroup_{{ $okr->id }}">
														<div class="col-12 overflow_hidd padd_0 div_okr panel" id="okr_{{ $okr->id }}" style="{!! $this_quarter != ('Q'.ceil(date("n", strtotime(date($okr->start_date))) / 3) .' - '. date("Y", strtotime(date($okr->start_date)))) ? 'display:none;' : '' !!}" >
															<div class="col-sm-8 col-4 float_l"><i class="fas fa-bullseye"></i><span class="padd_l_10">{{ $okr->name }}</span></div>
															<div class="col-sm-4 col-1 float_l">{!! $okr->employee ? $okr->employee->user->first_name . ' ' . $okr->employee->user->last_name : '<i class="fas fa-globe-europe" title="Svi"></i>' !!}</div>
															<div class="col-sm-3 col-1 float_l">
																{{'Q'.ceil(date("n", strtotime(date($okr->start_date))) / 3) .' - '. date("Y", strtotime(date($okr->start_date))) }}
															</div>
															<div class="col-sm-3 col-1 float_l">{{  date('d.m.Y', strtotime($okr->end_date)) }}</div>
															<div class="col-sm-6 col-2 float_l edit_progress editable not_link" >
																<span class="not_link">
																	@if ( $okr->progress > 0 )
																		{{ $okr->progress  }}
																	@else
																		{!! count($okr->hasKeyResults) > 0 ? round($okr->hasKeyResults->sum('progress') / (count($okr->hasKeyResults) * 100 ) * 100, 2) : 0 !!}%
																	@endif
																</span>
																<p class="progressBar {!! $employee->id == $okr->employee_id || Sentinel::inRole('uprava') || Sentinel::inRole('administrator') ? 'progressOkr' : '' !!} not_link" id="okr_{{ $okr->id }}"></p>
															</div>
															<div class="col-sm-8 col-2 float_l">{!!  $okr->comment ? $okr->comment : '-' !!}</div>
															<div class="col-sm-4 col-1 float_l center not_link">
																@if( Sentinel::inRole('uprava') || Sentinel::inRole('administrator') || $employee->id == $okr->employee_id )
																	@if(Sentinel::getUser()->hasAccess(['okrs.update']) || in_array('okrs.update', $permission_dep))
																		<a href="{{ route('okrs.edit', $okr->id) }}" class="btn-edit not_link" rel="modal:open">
																				<i class="far fa-edit not_link"></i>
																		</a>
																	@endif
																	@if(Sentinel::getUser()->hasAccess(['key_results.create']) || in_array('key_results.create', $permission_dep))
																		<a href="{{ route('key_results.create',['okr_id' => $okr->id]) }}" class="btn-create not_link" rel="modal:open" title="Dodaj ključni rezultat">
																			<i class="far fa-plus-square not_link"></i>
																		</a>
																	@endif
																	@if( Sentinel::getUser()->hasAccess(['okrs.delete']) || in_array('okrs.delete', $permission_dep))
																		<a href="{{ route('okrs.destroy', $okr->id) }}" class="action_confirm btn-delete danger not_link" data-token="{{ csrf_token() }}"  data-title="del_okrgroup_{{ $okr->id }}">
																			<i class="far fa-trash-alt not_link"></i>
																		</a>
																	@endif
																@endif
															</div>
														</div>
														<div class="col-12 overflow_hidd padd_0 div_keyResults panel" id="okr1_{{ $okr->id }}">
															@if (count($all_keyResults) > 0)
																@foreach ($all_keyResults->where('okr_id', $okr->id ) as $keyResults)
																	<div class="col-12 overflow_hidd padd_0 keyResults panel" id="key_{{ $keyResults->id }}" style="{!! $this_quarter != ('Q'.ceil(date("n", strtotime(date($keyResults->okr->start_date))) / 3) .' - '. date("Y", strtotime(date($keyResults->okr->start_date)))) ? 'display:none;' : '' !!}">
																		<div class="col-sm-8 col-4 float_l"><i class="fas fa-key"></i><span class="padd_l_10">{{ $keyResults->name }}</span></div>
																		<div class="col-sm-4 col-1 float_l">{!! $keyResults->employee ? $keyResults->employee->user->first_name . ' ' . $keyResults->employee->user->last_name : '<i class="fas fa-globe-europe" title="Svi"></i>' !!}</div>
																		<div class="col-sm-3 col-1 float_l">
																			{{'Q'.ceil(date("n", strtotime(date($keyResults->okr->start_date))) / 3) .' - '. date("Y", strtotime(date($keyResults->okr->start_date))) }}
																		</div>
																		<div class="col-sm-3 col-1 float_l">{{ date('d.m.Y', strtotime($keyResults->end_date)) }}</div>
																		<div class="col-sm-6 col-2 float_l edit_progress editable not_link" >
																			<span class="not_link">
																				@if( $keyResults->progress > 0)
																					{{ $keyResults->progress . '%'}}
																				@else
																					{!! count($keyResults->hasTasks) > 0 ? round($keyResults->hasTasks->sum('progress') / (count($keyResults->hasTasks) * 100 ) * 100, 2) : 0 !!}%
																				@endif
																			</span>
																			<p class="progressBar {!! Sentinel::inRole('uprava') || Sentinel::inRole('administrator') || $employee->id == $keyResults->employee_id ? 'progressResult' : '' !!} not_link" id="{{ $keyResults->id }}"></p>
																		</div>
																		<div class="col-sm-8 col-2 float_l">{!! $keyResults->comment ? $keyResults->comment : '-' !!}</div>
																		<div class="col-sm-4 col-1 float_l center not_link">
																			@if( Sentinel::inRole('uprava') || Sentinel::inRole('administrator') || $employee->id == $keyResults->employee_id )
																				@if(Sentinel::getUser()->hasAccess(['key_results.update']) || in_array('key_results.update', $permission_dep))
																					<a href="{{ route('key_results.edit', $keyResults->id) }}" class="btn-edit not_link" rel="modal:open">
																							<i class="far fa-edit not_link"></i>
																					</a>
																				@endif
																				@if(Sentinel::getUser()->hasAccess(['key_result_tasks.create']) || in_array('key_result_tasks.create', $permission_dep))
																					<a href="{{ route('key_result_tasks.create',['keyResults_id' => $keyResults->id]) }}" class="btn-create not_link" rel="modal:open" title="Dodaj zadatak" >
																						<i class="far fa-plus-square not_link"></i>
																					</a>
																				@endif
																				@if( Sentinel::getUser()->hasAccess(['key_results.delete']) || in_array('key_results.delete', $permission_dep))
																					<a href="{{ route('key_results.destroy', $keyResults->id) }}" class="action_confirm btn-delete danger not_link" data-token="{{ csrf_token() }}"  data-title="del_okr1_{{ $okr->id }}">
																						<i class="far fa-trash-alt not_link"></i>
																					</a>
																				@endif
																			@endif
																		</div>
																	</div>
																	<div class="col-12 overflow_hidd padd_0 div_keyResultTasks panel" id="result_{{ $keyResults->id }}">
																		@if (count($all_keyResultTasks) > 0)
																			@foreach ($all_keyResultTasks->where('keyresult_id',$keyResults->id) as $task)
																				<div class="col-12 overflow_hidd padd_0 keyResultTask panel" id="task_{{$task->id}}" style="{!! $this_quarter != ('Q'.ceil(date("n", strtotime(date($task->keyResult->okr->start_date))) / 3) .' - '. date("Y", strtotime(date($task->keyResult->okr->start_date))) ) ? 'display:none;' : '' !!}">
																					<div class="col-sm-8 col-4 float_l"><i class="fas fa-tasks"></i><span class="padd_l_10">{{ $task->name }}</span></div>
																					<div class="col-sm-4 col-1 float_l">{!! $task->employee ? $task->employee->user->first_name . ' ' . $task->employee->user->last_name : '<i class="fas fa-globe-europe" title="Svi"></i>' !!}</div>
																					<div class="col-sm-3 col-1 float_l">
																						{{'Q'.ceil(date("n", strtotime(date($task->keyResult->okr->start_date))) / 3) .' - '. date("Y", strtotime(date($task->keyResult->okr->start_date))) }}
																					</div>
																					<div class="col-sm-3 col-1 float_l">{{ date('d.m.Y', strtotime($task->end_date)) }}</div>
																					<div class="col-sm-6 col-2 float_l edit_progress editable not_link" >
																						<span class="not_link">{{ round($task->progress, 2) . '%' }}</span>
																						<p class="progressBar  {!! Sentinel::inRole('uprava') || Sentinel::inRole('administrator') || $employee->id == $task->employee_id ? 'progressTask' : '' !!} not_link" id="task_{{ $task->id }}"></p>
																					</div>
																					<div class="col-sm-8 col-2 float_l">{!! $task->comment ? $task->comment : '-' !!}</div>
																					<div class="col-sm-4 col-1 float_l center not_link">
																						@if( Sentinel::inRole('uprava') || Sentinel::inRole('administrator') || $employee->id == $task->employee_id )
																							@if(Sentinel::getUser()->hasAccess(['key_result_tasks.update']) || in_array('key_result_tasks.update', $permission_dep))
																								<a href="{{ route('key_result_tasks.edit', $task->id) }}" class="btn-edit not_link" rel="modal:open">
																										<i class="far fa-edit not_link"></i>
																								</a>
																							@endif
																							@if( Sentinel::getUser()->hasAccess(['key_result_tasks.delete']) || in_array('key_result_tasks.delete', $permission_dep))
																								<a href="{{ route('key_result_tasks.destroy', $task->id) }}" class="action_confirm btn-delete danger not_link" data-token="{{ csrf_token() }}" data-title="del_result_{{$keyResults->id }}">
																									<i class="far fa-trash-alt not_link"></i>
																								</a>
																							@endif
																						@endif
																					</div>
																				</div>
																			@endforeach
																		@else
																			<div class="col-12 overflow_hidd padd_0 keyResultTask panel" >
																				<div class="no_Tasks">Nema upisanih zadataka</div>
																			</div>
																		@endif
																	</div>
																@endforeach
															@else
																<div class="col-12 overflow_hidd padd_0 keyResults panel">
																	<div class="col-12 float_l">Nema upisanih ključnih rezultata</div>
																</div>	
															@endif
														</div>
													</div>
												@endif
											@endforeach
										</div>
									</section>
								@else
									<p class="no_data">@lang('basic.no_data')</p>
								@endif
							</section>
						</div>
						<div id="myOkr" class="tabcontent">
							<section>
								<h4>Moji OKR</h4>
								<section class="col-12 overflow_hidd section_okr">
									<div class="col-12 overflow_hidd div_header">
										<div class="col-sm-8 col-4 float_l">@lang('basic.name')</div>
										<div class="col-sm-4 col-1 float_l">@lang('basic.employee')</div>
										<div class="col-sm-4 col-1 float_l capitalize">@lang('basic.quarter')</div>
										<div class="col-sm-4 col-1 float_l capitalize">@lang('absence.end_date')</div>
										<div class="col-sm-4 col-2 float_l">@lang('basic.progress') <i class="fas fa-pencil-alt"></i></div>
										<div class="col-sm-8 col-2 float_l">@lang('basic.comment')</div>
										<div class="col-sm-4 col-1 float_l">@lang('basic.options')</div>
									</div>
									<div class="col-12 overflow_hidd div_main">
										@if( $employee_okrs && count( $employee_okrs ) > 0)
											@foreach ($employee_okrs as $okr)
												<div class="okr_group" id="myokrgroup_{{ $okr->id }}">
													<div class="col-12 overflow_hidd padd_0 div_okr panel" id="my_okr_{{ $okr->id }}" style="{!! $this_quarter != ('Q'.ceil(date("n", strtotime(date($okr->start_date))) / 3) .' - '. date("Y", strtotime(date($okr->start_date))) ) ? 'display:none;' : '' !!}">
														<div class="col-sm-8 col-4 float_l"><i class="fas fa-bullseye"></i><span class="padd_l_10">{{ $okr->name }}</span></div>
														<div class="col-sm-4 col-1 float_l">{!! $okr->employee ? $okr->employee->user->first_name . ' ' . $okr->employee->user->last_name : '<i class="fas fa-globe-europe" title="Svi"></i>' !!}</div>
														<div class="col-sm-3 col-1 float_l">
															{{'Q'.ceil(date("n", strtotime(date($okr->start_date))) / 3) .' - '. date("Y", strtotime(date($okr->start_date))) }}
														</div>
														<div class="col-sm-3 col-1 float_l">{{  date('d.m.Y', strtotime($okr->end_date)) }}</div>
														<div class="col-sm-6 col-2 float_l edit_progress editable not_link" >
															<span class="not_link">
																@if ( $okr->progress > 0 )
																	{{ $okr->progress  }}%
																@else
																	{!! count($okr->hasKeyResults) > 0 ? round($okr->hasKeyResults->sum('progress') / (count($okr->hasKeyResults) * 100 ) * 100, 2) : 0 !!}%
																@endif
															</span>
															<p class="progressBar {!! Sentinel::inRole('uprava') || Sentinel::inRole('administrator') || $employee->id == $okr->employee_id ? 'progressOkr' : '' !!} not_link" id="okr_{{ $okr->id }}"></p>
														</div>
														<div class="col-sm-8 col-2 float_l">{!!  $okr->comment ? $okr->comment : '-' !!}</div>
														<div class="col-sm-4 col-1 float_l center not_link">
															@if( Sentinel::inRole('uprava') || Sentinel::inRole('administrator') || $employee->id == $okr->employee_id )
																@if(Sentinel::getUser()->hasAccess(['okrs.update']) || in_array('okrs.update', $permission_dep))
																	<a href="{{ route('okrs.edit', $okr->id) }}" class="btn-edit not_link" rel="modal:open">
																			<i class="far fa-edit not_link"></i>
																	</a>
																@endif
																@if(Sentinel::getUser()->hasAccess(['key_results.create']) || in_array('key_results.create', $permission_dep))
																	<a href="{{ route('key_results.create',['okr_id' => $okr->id]) }}" class="btn-create not_link" rel="modal:open" title="Dodaj ključni rezultat">
																		<i class="far fa-plus-square not_link"></i>
																	</a>
																@endif
																@if( Sentinel::getUser()->hasAccess(['okrs.delete']) || in_array('okrs.delete', $permission_dep))
																	<a href="{{ route('okrs.destroy', $okr->id) }}" class="action_confirm btn-delete danger not_link" data-token="{{ csrf_token() }}" data-title="del_myokrgroup_{{ $okr->id }}">
																		<i class="far fa-trash-alt not_link"></i>
																	</a>
																@endif
															@endif
														</div>
													</div>
													<div class="col-12 overflow_hidd padd_0 div_keyResults panel" id="my_okr1_{{ $okr->id }}">
														@if (count($okr->hasKeyResults) > 0)
															@foreach ($okr->hasKeyResults as $keyResults)
																<div class="col-12 overflow_hidd padd_0 keyResults panel" id="my_key_{{ $keyResults->id }}" style=" {!! $this_quarter != ('Q'.ceil(date("n", strtotime(date($keyResults->okr->start_date))) / 3) .' - '. date("Y", strtotime(date($keyResults->okr->start_date)))) ? 'display:none;' : '' !!}">
																	<div class="col-sm-8 col-4 float_l"><i class="fas fa-key"></i><span class="padd_l_10">{{ $keyResults->name }}</span></div>
																	<div class="col-sm-4 col-1 float_l">{!! $keyResults->employee ? $keyResults->employee->user->first_name . ' ' . $keyResults->employee->user->last_name : '<i class="fas fa-globe-europe" title="Svi"></i>' !!}</div>
																	<div class="col-sm-3 col-1 float_l">
																		{{'Q'.ceil(date("n", strtotime(date($keyResults->okr->start_date))) / 3) .' - '. date("Y", strtotime(date($keyResults->okr->start_date))) }}																	
																	</div>
																	<div class="col-sm-3 col-1 float_l">{{ date('d.m.Y', strtotime($keyResults->end_date)) }}</div>
																	<div class="col-sm-6 col-2 float_l edit_progress editable not_link" >
																		<span class="not_link">
																			@if( $keyResults->progress > 0 )
																				{{ $keyResults->progress . '%' }}
																			@else
																				@if (count($keyResults->hasTasks) > 0 )
																					{{ round($keyResults->hasTasks->sum('progress') / (count($keyResults->hasTasks) * 100 ) * 100, 2) }}
																				@else
																					0
																				@endif
																			@endif
																		</span>
																		<p class="progressBar {!! Sentinel::inRole('uprava') || Sentinel::inRole('administrator') || $employee->id == $keyResults->employee_id ? 'progressResult' : '' !!} not_link" id="{{ $keyResults->id }}"></p>
																	</div>
																	<div class="col-sm-8 col-2 float_l">{!! $keyResults->comment ? $keyResults->comment : '-' !!}</div>
																	<div class="col-sm-4 col-1 float_l center not_link">
																		@if( Sentinel::inRole('uprava') || Sentinel::inRole('administrator') || $employee->id == $keyResults->employee_id )
																			@if(Sentinel::getUser()->hasAccess(['key_results.update']) || in_array('key_results.update', $permission_dep))
																				<a href="{{ route('key_results.edit', $keyResults->id) }}" class="btn-edit not_link" rel="modal:open">
																						<i class="far fa-edit not_link"></i>
																				</a>
																			@endif
																			@if(Sentinel::getUser()->hasAccess(['key_result_tasks.create']) || in_array('key_result_tasks.create', $permission_dep))
																				<a href="{{ route('key_result_tasks.create',['keyResults_id' => $keyResults->id]) }}" class="btn-create not_link" rel="modal:open" title="Dodaj zadatak" >
																					<i class="far fa-plus-square not_link"></i>
																				</a>
																			@endif
																			@if( Sentinel::getUser()->hasAccess(['key_results.delete']) || in_array('key_results.delete', $permission_dep))
																				<a href="{{ route('key_results.destroy', $keyResults->id) }}" class="action_confirm btn-delete danger not_link" data-token="{{ csrf_token() }}" data-title="del_my_okr1_{{ $okr->id }}">
																					<i class="far fa-trash-alt not_link"></i>
																				</a>
																			@endif
																		@endif
																	</div>
																</div>
																<div class="col-12 overflow_hidd padd_0 div_keyResultTasks panel" id="my_result_{{$keyResults->id }}">
																	@if (count($keyResults->hasTasks) > 0)
																		@foreach ($keyResults->hasTasks as $task)
																			<div class="col-12 overflow_hidd padd_0 keyResultTask panel" style="{!! $this_quarter != ('Q'.ceil(date("n", strtotime(date($task->keyResult->okr->start_date))) / 3) .' - '. date("Y", strtotime(date($task->keyResult->okr->start_date)))) ? 'display:none;' : '' !!}"  id="mytask1_{{$task->id}}">
																				<div class="col-sm-8 col-4 float_l"><i class="fas fa-tasks"></i><span class="padd_l_10">{{ $task->name }}</span></div>
																				<div class="col-sm-4 col-1 float_l">{!! $task->employee ? $task->employee->user->first_name . ' ' . $task->employee->user->last_name : '<i class="fas fa-globe-europe" title="Svi"></i>' !!}</div>
																				<div class="col-sm-3 col-1 float_l">
																					{{'Q'.ceil(date("n", strtotime(date($task->keyResult->okr->start_date))) / 3) .' - '. date("Y", strtotime(date($task->keyResult->okr->start_date))) }}																			
																				</div>
																				<div class="col-sm-3 col-1 float_l">{{ date('d.m.Y', strtotime($task->end_date)) }}</div>
																				<div class="col-sm-6 col-2 float_l edit_progress editable not_link" >
																					<span class="not_link">{{ $task->progress . '%' }}</span>
																					<p class="progressBar {!! Sentinel::inRole('uprava') || Sentinel::inRole('administrator') || $employee->id == $task->employee_id ? 'progressTask' : '' !!} not_link" id="task_{{ $task->id }}"></p>
																				</div>
																				<div class="col-sm-8 col-2 float_l">{!! $task->comment ? $task->comment : '-' !!}</div>
																				<div class="col-sm-4 col-1 float_l center not_link">
																					@if( Sentinel::inRole('uprava') || Sentinel::inRole('administrator') || $employee->id == $task->employee_id )
																						@if(Sentinel::getUser()->hasAccess(['key_result_tasks.update']) || in_array('key_result_tasks.update', $permission_dep))
																							<a href="{{ route('key_result_tasks.edit', $task->id) }}" class="btn-edit not_link" rel="modal:open">
																									<i class="far fa-edit not_link"></i>
																							</a>
																						@endif
																						@if( Sentinel::getUser()->hasAccess(['key_result_tasks.delete']) || in_array('key_result_tasks.delete', $permission_dep))
																							<a href="{{ route('key_result_tasks.destroy', $task->id) }}" class="action_confirm btn-delete danger not_link" data-token="{{ csrf_token() }}" data-title="del_my_result_{{$keyResults->id }}">
																								<i class="far fa-trash-alt not_link"></i>
																							</a>
																						@endif
																					@endif
																				</div>
																			</div>
																		@endforeach
																	@else
																		<div class="col-12 overflow_hidd padd_0 keyResultTask panel">
																			<div class="no_Tasks">Nema upisanih zadataka</div>
																		</div>
																	@endif
																</div>
															@endforeach
														@else
															<div class="col-12 overflow_hidd padd_0 keyResults panel">
																<div class="col-12 float_l">Nema upisanih ključnih rezultata</div>
															</div>	
														@endif
													</div>
												</div>
											@endforeach
										@endif
									</div>
								</section>
							<section>
						</div>
						<div id="myResults" class="col-12 overflow_hidd padd_0 my_div_keyResults tabcontent" id="my_div_keyResults">
							<section>
								<h4>Moji ključni rezultati</h4>
								<section class="col-12 overflow_hidd section_okr">
									<div class="col-12 overflow_hidd div_header">
										<div class="col-sm-8 col-4 float_l">@lang('basic.name')</div>
										<div class="col-sm-4 col-1 float_l">@lang('basic.employee')</div>
										<div class="col-sm-3 col-1 float_l capitalize">@lang('basic.quarter')</div>
										<div class="col-sm-3 col-1 float_l capitalize">@lang('absence.end_date')</div>
										<div class="col-sm-6 col-2 float_l">@lang('basic.progress') <i class="fas fa-pencil-alt"></i></div>
										<div class="col-sm-8 col-2 float_l">@lang('basic.comment')</div>
										<div class="col-sm-4 col-1 float_l">@lang('basic.options')</div>
									</div>
									@if( $employee_key_results && count( $employee_key_results ) > 0)
										@foreach ($employee_key_results as $keyResults)
											<div class="col-12 overflow_hidd padd_0 keyResults panel" id="my_key_{{ $keyResults->id }}" style="{!! $this_quarter != ('Q'.ceil(date("n", strtotime(date($keyResults->okr->start_date))) / 3) .' - '. date("Y", strtotime(date($keyResults->okr->start_date))) ) ? 'display:none;' : '' !!}">
												<div class="col-sm-8 col-4 float_l"><i class="fas fa-key"></i><span class="padd_l_10">{{ $keyResults->name }}</span></div>
												<div class="col-sm-4 col-1 float_l">{!! $keyResults->employee ? $keyResults->employee->user->first_name . ' ' . $keyResults->employee->user->last_name : '<i class="fas fa-globe-europe" title="Svi"></i>' !!}</div>
												<div class="col-sm-3 col-1 float_l">
													{{'Q'.ceil(date("n", strtotime(date($keyResults->okr->start_date))) / 3) .' - '. date("Y", strtotime(date($keyResults->okr->start_date))) }}
												</div>
												<div class="col-sm-3 col-1 float_l">{{ date('d.m.Y', strtotime($keyResults->end_date)) }}</div>
												<div class="col-sm-6 col-2 float_l edit_progress editable not_link" >
													<span>
														@if ($keyResults->progress > 0 )
															{{ $keyResults->progress }}%
														@else
															{!! count($keyResults->hasTasks) > 0 ? round($keyResults->hasTasks->sum('progress') / (count($keyResults->hasTasks) * 100 ) * 100,2) : 0 !!}%
														@endif
													</span>
													<p class="progressBar {!! Sentinel::inRole('uprava') || Sentinel::inRole('administrator') || $employee->id == $keyResults->employee_id ? 'progressResult' : '' !!} not_link" id="{{ $keyResults->id }}"></p>
												</div>
												<div class="col-sm-8 col-2 float_l">{!! $keyResults->comment ? $keyResults->comment : '-' !!}</div>
												<div class="col-sm-4 col-1 float_l center not_link">
													@if( Sentinel::inRole('uprava') || Sentinel::inRole('administrator') || $employee->id == $keyResults->employee_id )
														@if(Sentinel::getUser()->hasAccess(['key_results.update']) || in_array('key_results.update', $permission_dep))
															<a href="{{ route('key_results.edit', $keyResults->id) }}" class="btn-edit not_link" rel="modal:open">
																	<i class="far fa-edit not_link"></i>
															</a>
														@endif												
														@if(Sentinel::getUser()->hasAccess(['key_result_tasks.create']) || in_array('key_result_tasks.create', $permission_dep))
															<a href="{{ route('key_result_tasks.create',['keyResults_id' => $keyResults->id]) }}" class="btn-create not_link" rel="modal:open" title="Dodaj zadatak" >
																<i class="far fa-plus-square not_link"></i>
															</a>
														@endif
														@if( Sentinel::getUser()->hasAccess(['key_results.delete']) || in_array('key_results.delete', $permission_dep))
															<a href="{{ route('key_results.destroy', $keyResults->id) }}" class="action_confirm btn-delete danger not_link" data-token="{{ csrf_token() }}"  data-title="del_my_div_keyResults">
																<i class="far fa-trash-alt not_link"></i>
															</a>
														@endif
													@endif
												</div>
											</div>
											<div class="col-12 overflow_hidd padd_0 div_keyResultTasks panel" id="my_result2_{{$keyResults->id }}">
												@if (count($keyResults->hasTasks) > 0)
													@foreach ($keyResults->hasTasks as $task)
														<div class="col-12 overflow_hidd padd_0 keyResultTask panel"   id="mytask2_{{$task->id}}" style="{!! $this_quarter != ('Q'.ceil(date("n", strtotime(date($task->keyResult->okr->start_date))) / 3) .' - '. date("Y", strtotime(date($task->keyResult->okr->start_date)))) ? 'display:none;' : '' !!}">
															<div class="col-sm-8 col-4 float_l"><i class="fas fa-tasks"></i><span class="padd_l_10">{{ $task->name }}</span></div>
															<div class="col-sm-4 col-1 float_l">{!! $task->employee ? $task->employee->user->first_name . ' ' . $task->employee->user->last_name : '<i class="fas fa-globe-europe" title="Svi"></i>' !!}</div>
															<div class="col-sm-3 col-1 float_l">
																{{'Q'.ceil(date("n", strtotime(date($task->keyResult->okr->start_date))) / 3) .' - '. date("Y", strtotime(date($task->keyResult->okr->start_date))) }}
															</div>
															<div class="col-sm-3 col-1 float_l">{{ date('d.m.Y', strtotime($task->end_date)) }}</div>
															<div class="col-sm-6 col-2 float_l edit_progress editable not_link" >
																<span class="not_link">{{ $task->progress . '%' }} </span>
																<p class="progressBar {!! Sentinel::inRole('uprava') || Sentinel::inRole('administrator') || $employee->id == $task->employee_id ? 'progressTask' : '' !!} not_link" id="task_{{ $task->id }}"></p>
															</div>
															<div class="col-sm-8 col-2 float_l">{!! $task->comment ? $task->comment : '-' !!}</div>
															<div class="col-sm-4 col-1 float_l center not_link">
																@if( Sentinel::inRole('uprava') || Sentinel::inRole('administrator') || $employee->id == $task->employee_id )
																	@if(Sentinel::getUser()->hasAccess(['key_result_tasks.update']) || in_array('key_result_tasks.update', $permission_dep))
																		<a href="{{ route('key_result_tasks.edit', $task->id) }}" class="btn-edit not_link" rel="modal:open">
																				<i class="far fa-edit not_link"></i>
																		</a>
																	@endif
																	@if( Sentinel::getUser()->hasAccess(['key_result_tasks.delete']) || in_array('key_result_tasks.delete', $permission_dep))
																		<a href="{{ route('key_result_tasks.destroy', $task->id) }}" class="action_confirm btn-delete danger not_link" data-token="{{ csrf_token() }}" data-title="del_result2_{{$keyResults->id }}">
																			<i class="far fa-trash-alt not_link"></i>
																		</a>
																	@endif
																@endif
															</div>
														</div>
													@endforeach
												@else
													<div class="col-12 overflow_hidd padd_0 keyResultTask panel">
														<div class="no_Tasks">Nema upisanih zadataka</div>
													</div>
												@endif
											</div>
										@endforeach
									@endif
								</section>
							</section>
						</div>
						<div id="myTasks" class="col-12 overflow_hidd padd_0 my_div_keyResultTasks panel tabcontent" id="my_div_keyResultTasks" >
							<section>
								<h4>Moji Zadaci</h4>
								<section class="col-12 overflow_hidd section_okr">
									<div class="col-12 overflow_hidd div_header">
										<div class="col-sm-8 col-4 float_l">@lang('basic.name')</div>
										<div class="col-sm-4 col-1 float_l">@lang('basic.employee')</div>
										<div class="col-sm-3 col-1 float_l capitalize">@lang('basic.quarter')</div>
										<div class="col-sm-3 col-1 float_l capitalize">@lang('absence.end_date')</div>
										<div class="col-sm-6 col-2 float_l">@lang('basic.progress') <i class="fas fa-pencil-alt"></i></div>
										<div class="col-sm-8 col-2 float_l">@lang('basic.comment')</div>
										<div class="col-sm-4 col-1 float_l">@lang('basic.options')</div>
									</div>
									@if( $employee_key_result_tasks && count( $employee_key_result_tasks ) > 0)
										@foreach ($employee_key_result_tasks as $task)
											<div class="col-12 overflow_hidd padd_0 keyResultTask panel" id="mytask3_{{$task->id}}" style="{!! $this_quarter != ('Q'.ceil(date("n", strtotime(date($task->keyResult->okr->start_date))) / 3) .' - '. date("Y", strtotime(date($task->keyResult->okr->start_date)))) ? 'display:none;' : '' !!}">
												<div class="col-sm-8 col-4 float_l"><i class="fas fa-tasks"></i><span class="padd_l_10">{{ $task->name }}</span></div>
												<div class="col-sm-4 col-1 float_l">{!! $task->employee ? $task->employee->user->first_name . ' ' . $task->employee->user->last_name : '<i class="fas fa-globe-europe" title="Svi"></i>' !!}</div>
												<div class="col-sm-3 col-1 float_l">
													{{'Q'.ceil(date("n", strtotime(date($task->keyResult->okr->start_date))) / 3) .' - '. date("Y", strtotime(date($task->keyResult->okr->start_date))) }}
												</div>
												<div class="col-sm-3 col-1 float_l">{{ date('d.m.Y', strtotime($task->end_date)) }}</div>
												<div class="col-sm-6 col-2 float_l edit_progress editable not_link" >
													<span class="not_link">{{ $task->progress . '%' }} </span>
													<p class="progressBar {!! Sentinel::inRole('uprava') || Sentinel::inRole('administrator') || $employee->id == $task->employee_id ? 'progressTask' : '' !!} not_link" id="task_{{ $task->id }}"></p>
												</div>
												<div class="col-sm-8 col-2 float_l">{!! $task->comment ? $task->comment : '-' !!}</div>
												<div class="col-sm-4 col-1 float_l center not_link">
													@if( Sentinel::inRole('uprava') || Sentinel::inRole('administrator') || $employee->id == $task->employee_id )
														@if(Sentinel::getUser()->hasAccess(['key_result_tasks.update']) || in_array('key_result_tasks.update', $permission_dep))
															<a href="{{ route('key_result_tasks.edit', $task->id) }}" class="btn-edit not_link" rel="modal:open">
																	<i class="far fa-edit not_link"></i>
															</a>
														@endif
														@if( Sentinel::getUser()->hasAccess(['key_result_tasks.delete']) || in_array('key_result_tasks.delete', $permission_dep))
															<a href="{{ route('key_result_tasks.destroy', $task->id) }}" class="action_confirm btn-delete danger not_link" data-token="{{ csrf_token() }}" data-title="del_result_my_div_keyResultTasks }}">
																<i class="far fa-trash-alt not_link"></i>
															</a>
														@endif
													@endif
												</div>
											</div>
										@endforeach
									@else
										<div class="col-12 overflow_hidd padd_0 keyResultTask panel">
											<div class="no_Tasks">Nema upisanih zadataka</div>
										</div>
									@endif
								</section>
							</section>
							
						</div>
					</section>
				</div>
			</main>
		</section>
	</main>
</div>
<script>
	$.getScript('/../js/okr.js');
</script>
@stop