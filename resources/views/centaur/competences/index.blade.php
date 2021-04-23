@extends('Centaur::layout')

@section('title', 'Kompetencije')

@section('content')
<div class="index_page competence_table">
	<main class="col-md-12 index_main main_documents float_right">
		<section>
			<div class="page-header header_document">
				<a class="link_back" href="{{ route('dashboard') }}" ><span class="curve_arrow_left"></span></a>
				Kompetencije
				@if (Sentinel::inRole('administrator')	)
					{{-- <a href="{{ route('competence_evaluations.index') }}" class="view_all">Evaluacija</a> --}}
					{{-- <a href="{{ route('competence_ratings.index') }}" class="view_all">Ocjene</a> --}}
					{{-- <a href="{{ route('competence_questions.index') }}" class="view_all">Pitanja</a>
					<a href="{{ route('competence_group_questions.index') }}" class="view_all" >Grupe pitanja</a> --}}
					{{-- <a href="{{ route('competence_departments.index') }}" class="view_all" >Odjeli</a> --}}
				@endif
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
						</div>
					</header>
					<section class="page-main main_competences">
						@if(count($competences))
							@if (Sentinel::inRole('administrator') || Sentinel::inRole('moderator') )
								<table id="index_table" class="display table table-hover sort_1_asc">
									<thead>
										<tr>
											<th class="align_l">Naziv</th>
											<th class="align_l">Status</th>
											<th class="align_l">Odjel</th>
											<th class="align_l">Radno mjesto</th>
											<th class="align_l">Ocjenjuje djelatnik</th>
											<th class="align_l">Grupe pitanja</th>
											<th class="align_l">Ocjene</th>
											<th class="not-export-column align_l">@lang('basic.options')</th>
									</thead>
									<tbody>
										@foreach ($competences as $competence)
											<tr class="tr_open_link_new_page tr" data-href="/competences/{{ $competence->id }}" >
												<td>{{ $competence->name . ' ' . $competence->description  }}</td>
												<td>{!! $competence->status == 1 ? __('basic.active') : __('basic.inactive') !!}</td>
												<td>
													@if ($competence->hasDepartments && count($competence->hasDepartments) > 0)
														@foreach ($competence->hasDepartments as $competence_department)
															@if( $competence_department->department )
																<p>{{  $competence_department->department->name }}</p>
															@endif
														@endforeach
													@endif
												</td>
												<td>
													@if ($competence->hasDepartments && count($competence->hasDepartments) > 0)
														@foreach ($competence->hasDepartments as $competence_department)
															@if( $competence_department->work )
																<p>{{ $competence_department->work->name }}</p>
															@endif
														@endforeach
													@endif
												</td>
												<td>{!! $competence->employee_id ? $competence->employee->user->first_name .' '. $competence->employee->user->last_name : '' !!}</td>
												<td class="not_link">
													@if ($competence->hasGroups && count( $competence->hasGroups ) > 0)
														@foreach ($competence->hasGroups as $key => $group)
															<p>
																{{ $key+1 }}. {{ $group->name }} [{{$group->id }}]
																@if(Sentinel::getUser()->hasAccess(["competence_group_questions.update"]) || in_array("competence_group_questions.update", $permission_dep) )
																	<a href="{{ route('competence_group_questions.edit', $group->id) }}" class="btn-edit"  title="Ispravi grupu pitanja" rel="modal:open">
																		<i class="far fa-edit"></i>
																	</a>
																@endif
																@if(Sentinel::getUser()->hasAccess(['competence_group_questions.delete']) || in_array('competence_group_questions.delete', $permission_dep))
																	<a href="{{ route('competence_group_questions.destroy', $group->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
																		<i class="far fa-trash-alt"></i>
																	</a>
																@endif
																<span class="show_button_upload" title="Upload" id="group{{ $group->id }}"><i class="fas fa-upload"></i></span>
															</p>
															<form class="upload_file group{{ $group->id }}" action="{{ action('CompetenceQuestionController@importQuestions', $group->id) }}" method="POST" enctype="multipart/form-data">
																<p>Pitanja za grupu {{ $group->name }}</p>
																<button class="btn-new">Upload</button>
																<input type="file" name="file" required />
																@csrf
															</form>
														@endforeach
													@endif
												</td>
												<td class="not_link">
													@if ($competence->hasRatings && count($competence->hasRatings) > 0 )
														@foreach ($competence->hasRatings as $competence_rating)
															<p>
																{{ $competence_rating->rating }} - {{ $competence_rating->description }}
																@if(Sentinel::getUser()->hasAccess(["competence_ratings.update"]) || in_array("competence_ratings.update", $permission_dep) )
																	<a href="{{ route('competence_ratings.edit', $competence_rating->id) }}" class="btn-edit"  title="Ispravi ocjenu" rel="modal:open">
																		<i class="far fa-edit"></i>
																	</a>
																@endif
																@if (count($competence_rating->hasEvaluations) == 0)
																	@if(Sentinel::getUser()->hasAccess(['competence_ratings.delete']) || in_array('competence_ratings.delete', $permission_dep))
																		<a href="{{ route('competence_ratings.destroy', $competence_rating->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
																			<i class="far fa-trash-alt"></i>
																		</a>
																	@endif
																@endif
															</p>
														@endforeach
													@endif
												</td>
												<td class="center not_link">
													@if(Sentinel::getUser()->hasAccess(['competence_evaluations.view']) || in_array('competence_evaluations.view', $permission_dep))
														<a href="{{ route('competence_evaluations.show', $competence->id ) }}" class="btn-edit" title="Rezultati evaluacije" >
															<i class="fas fa-poll"></i>
														</a>
													@endif
													@if(Sentinel::getUser()->hasAccess(['competences.update']) || in_array('competences.update', $permission_dep))
														<a href="{{ route('competences.edit', $competence->id) }}" class="btn-edit" title="Ispravi upitnik" rel="modal:open">
																<i class="far fa-edit"></i>
														</a>
													@endif
													@if(Sentinel::getUser()->hasAccess(["competence_group_questions.create"]) || in_array("competence_group_questions.create", $permission_dep) )
														<a href="{{ route('competence_group_questions.create',['competence_id' => $competence->id ]) }}" class="btn-edit"  title="Dodaj grupu pitanja" rel="modal:open">
															<i class="fas fa-list"></i> 
														</a>
													@endif
													@if(Sentinel::getUser()->hasAccess(['competences.delete']) || in_array('competences.delete', $permission_dep))
														<a href="{{ route('competences.destroy', $competence->id) }}" class="action_confirm btn-delete danger" data-method="delete" data-token="{{ csrf_token() }}" title="{{ __('basic.delete')}}">
															<i class="far fa-trash-alt"></i>
														</a>
													@endif
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							@else
								<section>
									@foreach ( $competences as $competence )
										<article class="col-xs-12 col-sm-49 col-md-32 col-lg-24 col-xl-19 noticeboard_notice_body panel competence_body">
											@if ( $competence->employee && $competence->employee->id == Sentinel::getUser()->employee->id && $competence->status == 1 )
												<a href="{{ route('competence_evaluations.show', $competence->id) }}" >
											@else
												<a href="{{ route('competences.show', $competence->id) }}" >
											@endif
												<div>
													{{-- <header class="ad_header">
													</header> --}}
													<main class="competence_main">
														<span class="competence_content">{!! Config::get('app.locale') == 'uk' ? $competence->nameUKR : $competence->name !!}</span>
														<span class="competence_content"><b>{!! Config::get('app.locale') == 'uk' ? $competence->descriptionUKR : $competence->description !!}</b> </span>
													</main>
													{{-- <footer class="ad_footer">
														
													</footer> --}}
												</div>
											</a>
										</article>
									@endforeach
								</section>
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