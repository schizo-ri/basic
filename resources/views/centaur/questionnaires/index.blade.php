@extends('Centaur::layout')

@section('title', __('basic.documents'))
@php use App\Http\Controllers\QuestionnaireController; @endphp
@section('content')
<div class="index_page index_documents">
	<aside class="col-lg-12 col-xl-12 float_left">
		@include('Centaur::side_noticeboard')
	</aside>
	<main class="col-lg-12 col-xl-8 index_main main_documents float_right">
		<section>
			<div class="page-header header_questionnaire">
				<a class="link_back" href="{{ url()->previous() }}"><span class="curve_arrow_left"></span></a>
				@lang('questionnaire.questionnaires')
				<span class="show float_r">show<i class="fas fa-caret-down"></i></span>
				<span class="hide float_r">hide<i class="fas fa-caret-up"></i></span>
				<div class="preview_doc preview_q">
					<button id="left-button" class="scroll_left">
					</button>
					<span class="thumb_container float_l">
						@if(Sentinel::getUser()->hasAccess(['questionnaires.create']) || in_array('questionnaires.create', $permission_dep))
							<a class="add_new new_document new_questionnaire" href="{{ route('questionnaires.create') }}" rel="modal:open">
								<i class="fas fa-plus"></i>
							</a>
						@endif
					</span>
					@foreach ($questionnaires as $questionnaire)
						<span class="thumb_container">
							<span class="thumbnail thumbnail_q" title="" >
								<div class="thumb_content">
									<span class="progress_bar">
										@php
											$progress_perc = QuestionnaireController::progress_perc( $questionnaire->id);
											$progress_count = QuestionnaireController::progress_count( $questionnaire->id);
										@endphp
										<span class="progress" style="width:{!! $progress_perc !!}%"></span>
									</span>
									<span class="progress_val">{{ $progress_count  }}/{{count($employees)}}</span>
									<p>{{ $questionnaire->name }}</p>
									<span class="status_q">@if( $questionnaire->status == 1 && !count(QuestionnaireController::collectResults( $questionnaire->id)->where('employee_id', Sentinel::getUser()->employee->id)) >0) <a href="{{ route('questionnaires.show', $questionnaire->id) }}" rel="modal:open">Complete survey</a>@endif</span>
								</div>
							</span>
							<span class="thumb_name">{{ $questionnaire->name }}</span>
								<span class="thumb_time">{{ Carbon\Carbon::parse($questionnaire->created_at)
								->diffForHumans()  }}</span>
						</span>
					@endforeach
					<button id="right-button" class="scroll_right">
					</button>
				</div>
			</div>
			<main class="all_documents main_questionnaire">
				<div class="index_table_filter">
					<label>
						<input type="search" placeholder="Search" onkeyup="mySearchDoc()" id="mySearch">
					</label>
					<span class="change_view"></span>
					<span class="change_view2"></span>
					@if(Sentinel::getUser()->hasAccess(["questionnaires.create"]) || in_array("questionnaires.create", $permission_dep) )
						<a class="add_new" href="{{ route('questionnaires.create') }}" class="" rel="modal:open"><i style="font-size:11px" class="fa">&#xf067;</i></a>
					@endif
				</div>
				@if(count($questionnaires))
					<div class="table-responsive first_view">
						<table id="index_table" class="display table dataTable table-hover">
							<thead>
								<tr>
									<th>@lang('basic.name')</th>
									<th>Upload time</th>
									<th>Status</th>
									<th>Completion</th>
									<th class="not-export-column no-sort"></th>
								</tr>
							</thead>
							<tbody >
								@foreach ($questionnaires as $questionnaire)
									@php
										$progress_perc1 = QuestionnaireController::progress_perc( $questionnaire->id);
										$progress_count1 = QuestionnaireController::progress_count( $questionnaire->id);
									@endphp
									<tr>
										<td>
											@if(($questionnaire->status == 1 && ! count(QuestionnaireController::collectResults( $questionnaire->id)->where('employee_id', Sentinel::getUser()->employee->id)) > 0 ))
												<a class="qname" href="{{ route('questionnaires.show', $questionnaire->id) }}" rel="modal:open" >{{ $questionnaire->name }}</a>
											@elseif (count(QuestionnaireController::collectResults( $questionnaire->id)->where('employee_id', Sentinel::getUser()->employee->id)) > 0 )
												<a class="qname" href="{{ route('questionnaire_results.show', $questionnaire->id) }}" rel="modal:open" >{{ $questionnaire->name }}</a>		
											@else
												@if(Sentinel::inRole('administrator'))
													<a class="qname" href="{{ route('questionnaires.show', $questionnaire->id) }}" rel="modal:open" >{{ $questionnaire->name }}</a>
												@else
													<span class="qname" >{{ $questionnaire->name }}</span>
												@endif
											@endif
										</td>
										<td>{{ Carbon\Carbon::parse($questionnaire->created_at)->diffForHumans()  }}</td>
										<td>{!! $questionnaire->status == 1 ? 'aktivna' : 'neaktivna'!!}</td>
										<td class="q_progress">
											<span class="progress_bar">
												<span class="progress" style="height:{!! $progress_perc1 !!}%"></span>
											</span>
											<span class="progress_val">{{ $progress_count1 }} / {{count($employees)}}</span>
										</td>
										<td class="options center">
											@if(Sentinel::getUser()->hasAccess(['questionnaires.create']) || in_array('questionnaires.create', $permission_dep) || Sentinel::getUser()->hasAccess(['questionnaires.update']) || in_array('questionnaires.update', $permission_dep) || Sentinel::getUser()->hasAccess(['questionnaires.delete']) || in_array('questionnaires.delete', $permission_dep))
												<button class="collapsible option_dots float_r"></button>
												<div class="content">
													@if(Sentinel::getUser()->hasAccess(['questionnaires.create']) || in_array('questionnaires.create', $permission_dep))
														<a href="{{ action('QuestionnaireController@sendEmail', ['id' => $questionnaire->id ] ) }}" class="btn-edit"><i class="far fa-envelope"></i></a>
													@endif 
													@if(Sentinel::getUser()->hasAccess(['questionnaires.update']) || in_array('questionnaires.update', $permission_dep))
														<a href="{{ route('questionnaires.edit', $questionnaire->id) }}" class="btn-edit" rel="modal:open" ><i class="far fa-edit"  rel="modal:open"></i></a>
													@endif 
													@if(Sentinel::getUser()->hasAccess(['questionnaires.delete']) || in_array('questionnaires.delete', $permission_dep))
														<a href="{{ route('questionnaires.destroy', $questionnaire->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}"><i class="far fa-trash-alt"></i></a>
													@endif
												</div>
											@endif
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="table-responsive second_view">
						<div class="questionnaires">
							@foreach ($questionnaires as $questionnaire)
								<div class="thumb_container panel">
									<span class="thumbnail thumbnail_q" >
										<div class="thumb_content">
											<span class="progress_bar">
												<span class="progress" style="width:{!! $progress_perc1 !!}%"></span>
											</span>
											<span class="progress_val">{{ $progress_count1 }}/{{count($employees)}}</span>
											<p>{{ $questionnaire->name }}</p>
											@if( $questionnaire->status == 1 && !count(QuestionnaireController::collectResults( $questionnaire->id)->where('employee_id', Sentinel::getUser()->employee->id)) >0)<span class="status_q"><a href="{{ route('questionnaires.show', $questionnaire->id) }}" rel="modal:open">Complete survey</a></span>@endif
										</div>
									</span>
									<span class="thumb_name">{{ $questionnaire->name }}</span>
									<span class="thumb_time">{{ Carbon\Carbon::parse($questionnaire->created_at)->diffForHumans() }}</span>
								</div>
							@endforeach
						</div>
					</div>
				@else
					@lang('basic.no_data')
				@endif
			</main>
		</section>
	</main>
</div>
<script>
	$.getScript( '/../js/questionnaire.js');
</script>
@stop
