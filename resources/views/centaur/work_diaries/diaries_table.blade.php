@extends('Centaur::layout')

@section('title', __('basic.work_diary'))

@section('content')
<div class="index_page index_documents">
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
								<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
							</label>
							@if(Sentinel::getUser()->hasAccess(["work_diaries.create"]) || in_array("work_diaries.create", $permission_dep) )
								<a class="add_new" href="{{ route('work_diaries.create') }}" class="" rel="modal:open">
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
										<option value="{{ $key }}">{{ $workTask }}</option>
									@endforeach
								</select>
							</div>
							@if( Sentinel::inRole('administrator'))
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
							@endif
						</div>
					</header>
					<section class="page-main">
						@if(count($workDiaries))
							
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
