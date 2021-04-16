@extends('Centaur::layout')

@section('title', 'Evaluacija')

@section('content')
<div class="index_page competence_table">
	<main class="col-md-12 index_main main_documents float_right">
		<section>
			<div class="page-header header_document">
				<a class="link_back" href="{{ route('competences.index') }}" ><span class="curve_arrow_left"></span></a>
				Evaluacija kompetencije - {{ $competence->name }}
			</div>
			<main class="all_documents">
				<div class="table-responsive">
					<header class="page-header diary_header">
						<div class="index_table_filter">
							<label>
								<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearch()" id="mySearch">
							</label>
							@if(Sentinel::getUser()->hasAccess(["competences.create"]) || in_array("competences.create", $permission_dep) )
								<a class="add_new" href="{{ route('competences.create') }}" class="" rel="modal:open">
									<i class="fas fa-plus"></i>
								</a>
							@endif
							@if ( count($employees) > 0)
								<div class="div_select2">
									<select name="employee_id" class="select_filter filter_evaluation " style="border: none;font-size: inherit;color: inherit;" required >
										<option value="all" selected>Svi</option>
										@foreach ( $employees as $employee )
											<option value="{!! $employee->user->last_name  . ' ' . $employee->user->first_name !!}" data-id="{{ $employee->id }}" >{!! $employee->user->last_name  . ' ' . $employee->user->first_name !!}</option>
										@endforeach
									</select>
								</div>
							@endif
						</div>
					</header>
					<main class="competences_main">
						<section class="page-main main_competences">
							<div class="col-md-12 col-lg-6 float_left padd_tb_20">
								<form class="form_recommendation {!! ! $selected_employee ? 'display_none' : '' !!}" accept-charset="UTF-8" role="form" method="post" action="{{ route('improvement_recommendations.store') }}">
									<input type="hidden" name="employee_id" id="employee_id" value="{!! $selected_employee ? $selected_employee->id : '' !!}">
									<div class="col-8 float_left">
										<textarea class="form-control " name="comment" rows="3" placeholder="Preporuka za unaprijeđenje" required></textarea>
									</div>
									<div class="col-4 float_left">
										<input class="form-control margin_b_10 float_left " type="date" name="target_date" title="Datum">
										<select name="mentor" class="form-control select_filter mentor_filter float_left" >
											<option selected disabled>Dodjeli mentora</option>
											@foreach ( $all_employees as $all_employee )
												<option value="{{ $all_employee->id }}" >{!! $all_employee->last_name  . ' ' . $all_employee->first_name !!}</option>
											@endforeach
										</select>
									</div>
									@csrf
									<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
								</form>
							</div>
							<div class="col-md-12 col-lg-6  float_left">
								<ul class="legend">
									@foreach ($competence->hasRatings as $rating)
										<li>{{ $rating->rating . ' - ' . $rating->description}} </li>
									@endforeach
								</ul>	
							</div>
							<div>
								<form class="form_evaluation" accept-charset="UTF-8" role="form" method="post" action="{{ route('updateEvaluation') }}">
									<table id="index_table" class="display table table-hover sort_1_asc">
										<thead>
											<tr>
												<th class="align_l">Naziv</th>
												<th class="align_l">Ocjena</th>
												<th class="align_l">Bodovi</th>
												<th class="align_l">Opis ocjene</th>
												<th class="align_l">Ocjena nadređenog</th>
												<th class="align_l">Opis ocjene nadređenog</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($competence->hasGroups as $key_group => $group)
												<tr class="tr_group" data-id="{{ $group->id }}" >
													<td class="td_group" colspan="6"><span class="arrow_collapse padd_r_20"><i class="fas fa-angle-down"></i></span>{{ $group->name }}</td>										
												</tr>
												@foreach ($group->hasQuestions as $key_question => $question)
													<tr class="tr_questions" data-id="{{ $group->id }}" data-question="{{ $question->id }}">
														<td class="td_question" colspan="6"><span class="arrow_collapse padd_r_20"><i class="fas fa-angle-down"></i></span>{{ $question->name }}</td>
													</tr>
													@foreach ($question->hasEvaluations->groupBy('employee_id') as $employee_id => $evaluation)
														@php
															$evaluation_empl = $evaluation->where('user_id', $employee_id )->first();
															$evaluation_admin = $evaluation->where('user_id','<>',$employee_id )->first();
															if ($evaluation_empl) {
																$employee = $evaluation_empl->employee;
															} else {
																if ($evaluation_admin) {
																	$employee = $evaluation_admin->employee;
																}
															}
														@endphp
														<tr class="tr_evaluation" data-id="{{ $group->id }}" data-question="{{ $question->id }}">
															<td class="td_evaluation">{{ $employee->user->last_name . ' ' . $employee->user->first_name }}</td>
															<td class="td_evaluation">{!!  $evaluation_empl ? $evaluation_empl->rating->rating : '' !!}</td>
															<td class="td_evaluation rating_empl">{!! $evaluation_empl ? $evaluation_empl->rating->rating * $evaluation_empl->question->rating * $evaluation_empl->question->group->coefficient : '' !!}</td>
															<td class="td_evaluation">{!! $evaluation_empl ? $evaluation_empl->rating->description : '' !!}</td>
															<td class="td_evaluation edit_evaluation_id editable">
																@if( count($competence->hasRatings) > 0 )
																	@foreach ($competence->hasRatings as $key_rating => $rating)
																		<div class="rating_radio evaluate_manager">
																			<input 
																				type="radio" 
																				name="rating_id[{!! $evaluation_empl ? $evaluation_empl->id : $evaluation_admin->id !!}]"
																				value="{{ $rating->id }}" 
																				title="{!! $evaluation_empl ? $evaluation_empl->id : $evaluation_admin->id !!}" id="id[{!! $evaluation_empl ? $evaluation_empl->id : $evaluation_admin->id !!}][{{$key_rating}}]"
																				{!! $evaluation_admin && $evaluation_admin->where('rating_id', $rating->id )->first() ? 'checked' : '' !!}>
																			<label for="id[{!! $evaluation_empl ? $evaluation_empl->id : $evaluation_admin->id !!}][{{$key_rating}}]">{{ $rating->rating }}</label>
																		</div>
																	@endforeach
																@endif
															</td>
															<td class="td_evaluation edit_evaluation_comment editable">
																<textarea class="form-control evaluation_comment" name="comment" rows="3" title="{!! $evaluation_empl ? $evaluation_empl->id : $evaluation_admin->id !!}" >{!! $evaluation_admin ? $evaluation_admin->comment : '' !!}</textarea>
															</td>
														</tr>
													@endforeach
												@endforeach
											@endforeach
										</tbody>
									</table>
								</form>
							</div>
						</section>
					</main>
				</div>
			</main>
		</section>
	</main>
</div>
<script>
	$(function() {
		if( $('.competence_table').length > 0 ) {
			$('.tr_group').on('click',function(){
				var id = $( this ).attr('data-id');

				$('.tr_questions[data-id="'+id+'"]').toggle();
				if( $('.tr_questions[data-id="'+id+'"]:visible').length == 0 ) {
					$('.tr_evaluation[data-id="'+id+'"]').hide();
					$('.arrow_collapse').find('i , svg').remove();
					$('.arrow_collapse').prepend('<i class="fas fa-angle-down"></i>');
				
				} else {
					$( this ).find('.arrow_collapse').find('i , svg').remove();
					$( this ).find('.arrow_collapse').prepend('<i class="fas fa-angle-up"></i>');
				}
			});
			$('.tr_questions').on('click',function(){
				var id = $( this ).attr('data-question');
				$('.tr_evaluation[data-question="'+id+'"]').toggle();
				if( $('.tr_evaluation[data-question="'+id+'"]:visible').length == 0 ) {
					$( this ).find('.td_question').find('.arrow_collapse').find('i , svg').remove();
					$( this ).find('.td_question').find('.arrow_collapse').prepend('<i class="fas fa-angle-down"></i>');
				} else {
					$( this ).find('.td_question').find('.arrow_collapse').find('i , svg').remove();
					$( this ).find('.td_question').find('.arrow_collapse').prepend('<i class="fas fa-angle-up"></i>');
				}
			});
		}
	});
	
</script>
@stop