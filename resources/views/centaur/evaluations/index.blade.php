@extends('Centaur::layout')

@section('title', __('questionnaire.results'))
<link rel="stylesheet" href="{{ URL::asset('css/anketa.css') }}"/>
@section('content')
<div class="row">
    <div class="page-header">
		<a href="{{ route('questionnaires.index') }}" class="load_page">@lang('questionnaire.questionnaires')</a> / 
		<a href="{{ route('evaluation_categories.index') }}" class="load_page">@lang('questionnaire.evaluation_categories')</a> / 
		<a href="{{ route('evaluation_questions.index') }}" class="load_page">@lang('questionnaire.evaluation_questions')</a> / 
		<a href="{{ route('evaluation_ratings.index') }}" class="load_page">@lang('questionnaire.evaluation_ratings')</a>  / 
		<a href="{{ route('evaluations.index') }}" class="load_page">@lang('questionnaire.results')</a>
       
        <h1>@lang('questionnaire.results')</h1>
    </div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php
			$x = 0;
		?>
		@foreach($questionnaires as $questionnaire)
			@foreach($mjeseci as $mjesec)
				@if($evaluationEmployees->where('mm_yy',$mjesec)->where('questionnaire_id',$questionnaire->id )->first())
				<div class="table-responsive ">
					<details open>
						<summary>{{ $questionnaire->name . ' - ' .  $mjesec}}</summary>
						<table id="table_id[{{ $x }}]" class="display" style="width: 100%;">
							<thead>
								<tr>
									<th>Djelatnik</th>
									<th>Dao ocjena</th>
									<th>Dobio ocjena</th>
									<th>Ukupna ocjena</th>
								</tr>
							</thead>
							<tbody>
								@foreach($employees as $employee)
									<tr>
									<?php
										$ukupnaOcjena = 0;
										$ukupanRezultat = 0;
										$i = 0;
									?>
										<td><a href="{{ route('evaluations.show',['employee_id' =>  $employee->id, 'questionnaire_id' =>  $questionnaire->id, 'mjesec_godina' =>$mjesec ] ) }}">{{$employee->last_name  . ' ' . $employee->first_name }}</a></td>
										
										<td>{{ count($evaluationEmployees->where('employee_id', $employee->id)->where('mm_yy',$mjesec)->where('questionnaire_id',$questionnaire->id ))  }}</td>
										<td>{{ count($evaluationEmployees->where('ev_employee_id', $employee->id)->where('mm_yy',$mjesec)->where('questionnaire_id',$questionnaire->id ))  }}</td>
										@foreach($evaluationCategories as $evaluationCategory)
											<?php
												$rezultat = '';
												$ukupanRezultat = 0;
												
												foreach($evaluations->where('questionnaire_id',$questionnaire->id) as $evaluation){
													
													if($evaluation->employee_id == $employee->id && $evaluation->category_id == $evaluationCategory->id){
														$mjesec_godina = substr($evaluation->date,0,7);
														if($mjesec_godina == $mjesec){
															$i++;
															$ukupanRezultat += $evaluation->rating;		
														}
													}
												}
												if($ukupanRezultat === 0){
													$i = 1;
												} 
												$ukupnaOcjena += $ukupanRezultat;
											?>
										@endforeach
										<td>{{  number_format($ukupnaOcjena *0.25 / $i,2) . ' (' .  number_format(($ukupnaOcjena *0.25 / $i)*100 ,0) .'%)'}}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</details>
				</div>
				@endif
			@endforeach
		@endforeach
	</div>
</div>
@stop