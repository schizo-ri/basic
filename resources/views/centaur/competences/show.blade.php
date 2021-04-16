@extends('Centaur::layout')

@section('title', 'Evaluacija')

@section('content')
@php
/* 	dd(Config::get('app.locale')); */
@endphp
<div class="index_page competence_table">
	<main class="col-md-12 index_main main_documents float_right">
		<section>
			<div class="page-header header_document">
				<a class="link_back" href="{{ route('competences.index') }}" ><span class="curve_arrow_left"></span></a>
				{!! Config::get('app.locale') == 'uk' ? $competence->nameUKR : $competence->name !!}
			</div>
			<main class="all_documents">
				<div class="table-responsive">
					<header class="page-header ">
						<div class="index_table_filter">
							<label>
								<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearch()" id="mySearch">
							</label>
							@if(Sentinel::getUser()->hasAccess(["competences.create"]) || in_array("competences.create", $permission_dep) )
								<a class="add_new" href="{{ route('competences.create') }}" class="" rel="modal:open">
									<i class="fas fa-plus"></i>
								</a>
							@endif
						</div>
					</header>
					<section class="page-main competence_section">
						<div class="slideshow-container">
							<p>{{ $competence->description }}</p>
							<form accept-charset="UTF-8" role="form" method="post" action="{{ route('competence_evaluations.store') }}">
								<ul class="legend">
									@foreach ($competence->hasRatings as $rating)
										<li>{{ $rating->rating . ' - ' }} {!! Config::get('app.locale') == 'uk' ? $rating->descriptionUKR : $rating->description !!} </li>
									@endforeach
								</ul>
								<div>
									<input type="hidden" name="id" value="{{ $competence->id }}" >
									<div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
										@if (Sentinel::inRole('administrator') || $competence->employee_id == $this_employee->id )
											@if ( count($employees) > 0)
												<p class="eval_employee"><span>Ocijeni djelatnika </span>
													<select name="employee_id" class="select_filter " style="border: none;font-size: inherit;color: inherit;" required>
														@foreach ( $employees as $employee )
															@if ( ! $competence->hasEvaluations->where('employee_id',$employee->id)->first() )
																<option value="{{ $employee->id }}">{!! $employee->user->last_name  . ' ' . $employee->user->first_name !!}</option>
															@endif
														@endforeach
													</select>
												</p>
											@endif
										@else
											<input type="hidden" name="employee_id" value="{{ Sentinel::getUser()->employee->id }}">
										@endif
										@php
											$rating_all = 0;
										@endphp
										@foreach ($competence->hasGroups as $key_group => $group)
											@php
												$rating_group = 0;
											@endphp 
											<span hidden id="coefficient">{{ $group->coefficient }}</span>
											<input type="hidden" name="group_id[{{ $group->id }}]" value="{{ $group->id }}">
											<div class="mySlides">
												<h5>{{ ($key_group + 1) . '. '}} {!! Config::get('app.locale') == 'uk' ? $group->nameUKR : $group->name !!}</h5>
												<h6>{!! Config::get('app.locale') == 'uk' ? $group->descriptionUKR : $group->description !!}</h6>
												@if ( count($group->hasQuestions) > 0)
													<div>
														@foreach ($group->hasQuestions as $key_quest => $question)
															@php
																$rating_question = 0;
																$index = $question->rating * $group->coefficient;
																if ( Sentinel::inRole('administrator')) {
																	$evaluation = null;
																} else {
																	$evaluation = $question->hasEvaluations->where('employee_id', $this_employee->id)->first();
																	if($evaluation) {
																		$rating_question +=	round($index * $evaluation->rating->rating,2);
																		$rating_group += $rating_question;
																		$rating_all += $rating_question;
																	}
																}
															@endphp
															<input type="hidden" name="question_id[{{ $group->id }}][{{ $question->id }}]" value="{{ $question->id }}">
															<div class="clearfix">
																<div>
																	<p>{{ ($key_quest + 1) . '. '}} {!! Config::get('app.locale') == 'uk' ? $question->nameUKR : $question->name !!} {!! $evaluation ? ' - '. $rating_question .' - ' .  $evaluation->rating->description : '' !!}</p>
																	<p class="description">{!! Config::get('app.locale') == 'uk' ? $question->descriptionUKR : $question->description !!}</p>
																</div>
																<div class="overflow_hidd radio_group">
																	@if( count($competence->hasRatings) > 0 )
																		@foreach ($competence->hasRatings as $key => $rating)
																			<div class="rating_radio evaluate_user">
																				@if ( ! $evaluation )
																					<input type="radio" 
																					name="rating_id[{{ $group->id }}][{{ $question->id }}]" 
																					value="{{ $rating->id }}"
																					id="rating_id[{{ $group->id }}][{{ $question->id }}][{{$rating->id}}]" required >
																					<label class="label_rating" for="rating_id[{{ $group->id }}][{{ $question->id }}][{{$rating->id}}]">{{ $rating->rating }}</label>
																					<span class="span_question_rating" hidden>{{ $question->rating }}</span>
																				@endif
																			</div>
																		@endforeach
																	@endif
																</div>
															</div>
														@endforeach
													</div>
													<div class="rating_group"><b>@lang('basic.group_rating'): <span>{{ $rating_group }}</span></b></div>
													@if( ! $evaluations || count($evaluations) == 0)
														@if ( $key_group == count($competence->hasGroups)-1 )
															<input class="btn-submit" type="submit"  value="{{ __('basic.save')}}" id="stil1">
														@endif
													@endif
													<!-- Next and previous buttons -->
													@if (count($competence->hasGroups) > 1)
														@if ($key_group != (count($competence->hasGroups)-1))
															<a class="next btn-next">@lang('basic.next_tab') &#10095;</a>
														@endif
														@if ($key_group != 0)
															<a class="prev btn-next">&#10094; @lang('basic.prev_tab')</a>
														@endif
													@endif
												@endif
											</div>
										@endforeach
										<div class="rating_all"><b>@lang('basic.total_rating'): <span>{{ $rating_all }}</span></b></div>
									</div>
								</div>
								{{ csrf_field() }}
							</form>
							<!-- Next and previous buttons -->
							{{-- @if (count($competence->hasGroups) > 1)
								<a class="prev">&#10094;</a>
								<a class="next">&#10095;</a>
							@endif --}}
						</div>
					</section>
				</div>
			</main>
		</section>
	</main>
</div>
@stop
