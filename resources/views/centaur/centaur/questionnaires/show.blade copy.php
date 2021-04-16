<form accept-charset="UTF-8" role="form" method="post" action="{{ route('evaluations.store') }}">
	<div class="modal-header">
		<a class="link_back" rel="modal:close">
			<img src="{{ URL::asset('icons/arrow_left2.png') }}" />
		</a>
		<a href="#" class="btn-statistic" >
			<img class="img_statistic" src="{{ URL::asset('icons/curve-arrow_right.png') }}" alt="all notice" />
			<span>Results</span>
		</a>
		@if(Sentinel::getUser()->hasAccess(['questionnaires.update']) || in_array('questionnaires.update', $permission_dep) )
			<a href="{{ route('questionnaires.edit', $questionnaire->id) }}" class="btn-edit" rel="modal:open" >
				<img class="img_statistic" src="{{ URL::asset('icons/edit.png') }}" alt="edit" />
				<span>Edit</span>
			</a>
		@endif
		<div class="col-12 progr">
			<span class="progress_bar">
				@php
					$progress = 0;
				@endphp
				<span class="progress" style="width:30%"></span>
			</span>
			<span class="progress_val">2/10</span>
		</div>
		<h2 class="col-6 float_l">{{ $questionnaire->name }}</h2>
		
		<div class="col-6 float_l empl_select">
			<select name="ev_employee_id" class="ev_employee_id form-control" id="ev_employee_id1" required >
				<option value="" disabled selected ></option>
				@foreach($employees as $djelatnik)
					@if(! $evaluationEmployees->where('ev_employee_id',$djelatnik->id)->first())
						<option value="{{ $djelatnik->id }}">{{  $djelatnik->user['last_name'] . ' ' . $djelatnik->user['first_name'] }}</option>
					@endif
				@endforeach
			</select>
		</div>
		<!--
		<div class="dl_list">
			<dl>
				@foreach($evaluationRatings as $evaluationRating)
					<dt>{{ $evaluationRating->rating }}</dt>
					<dd>{{ $evaluationRating->name }}</dd><br>
				@endforeach
			</dl>
		</div>-->
	</div>
	<div class="modal-body">
		<div class="ime form-group" id="tip_ankete1" hidden >
			<h4>Prikaz ankete
			<select name="tip_ankete" class="tip_ankete form-control" id="tip_ankete" required>
				<option value="" disabled selected ></option>
				<option value="grupa">Grupirano</option>
				<option value="podgrupa">Pojedinačno</option>
			</select></h4>
		</div>
		<p id="user" hidden >{{ Sentinel::getUser()->first_name . ' ' . Sentinel::getUser()->last_name  }}</p>
		<input name="employee_id" type="hidden" id="employee_id" value ="{{ $employee->id }}" />
		<input name="questionnaire_id" type="hidden" value ="{{ $questionnaire->id }}" />
		<input name="datum" type="hidden" value ="{{ Carbon\Carbon::now()->format('Y-m-d') }}" />
		@php $i = 1; @endphp
		<section class="pitanja">
			<div class="anketa display_none" id="anketa_1">
				@foreach($evaluationCategories as $evaluationCategory)
					@if($evaluationQuestion->where('category_id', $evaluationCategory->id)->first())
						<h4 id="{{ $evaluationCategory->name_category }}">{{ $evaluationCategory->name_category }}</h4>
						@foreach( $evaluationQuestion->where('category_id', $evaluationCategory->id) as $question )
							<div class="pitanje">
								<input name="question_id[{{ $question->id }}]" type="hidden" value="{{ $question->id }}"  id="group_id1" />
								<p>
									<span class="rbr float_l">{{ $i }}</span>
									<span class="question float_l">{{ $question->name_question }}<br><span class="opis">{{ $question->description }}</span></span>
								</p>
								@php $i++; @endphp
								<span class="ocj">
									@foreach($evaluationRatings as $evaluationRating)
										<label class="container_radio">{{ $evaluationRating->rating }}
											<input type="radio" name="rating[{{ $question->id }}]" value="{{ $evaluationRating->rating }}" id="myRadio2{{ $question->id }}" required />
											<span class="checkmark"></span>
										</label>
									@endforeach 
								</span>
							</div>
						@endforeach
					@endif
				@endforeach
			</div>
			<div class="anketa display_none" id="anketa_2">
				@php $j = 1; @endphp
				@foreach($evaluationCategories as $evaluationCategory)
					@if($evaluationQuestion->where('category_id', $evaluationCategory->id)->first())
						<h4 id="{{ $evaluationCategory->name_category }}"><span class="rbr">{{ $j }}</span>{{ $evaluationCategory->name_category }}</h4>
							<input name="group_id[{{ $evaluationCategory->id }}]" type="hidden" value="{{ $evaluationCategory->id }}" id="group_id2" />
							@foreach( $evaluationQuestion->where('category_id', $evaluationCategory->id) as $question )
								<div class="pitanje">
									<p>{{ $question->name_question }}</p>
									<p class="opis">{{ $question->description }}</p>	
								</div>
							@endforeach
							<span class="ocj">
								@foreach($evaluationRatings as $evaluationRating)
									<label class="container_radio">{{ $evaluationRating->rating }}
										<input type="radio" name="rating[{{ $evaluationCategory->id }}]" value="{{ $evaluationRating->rating }}" id="myRadio2{{ $evaluationCategory->id }}" required />
										<span class="checkmark"></span>
									</label>
								@endforeach 
								
							</span>
						@php $j++; @endphp
					@endif
				@endforeach
			</div>
		</section>
		{{ csrf_field() }}
		<input class="btn-submit fill" type="submit" value="{{ __('basic.save')}}">
	</div>
</form>
<script >
	$(function(){
	//	$.getScript( 'js/questionnaire_show.js');
	});
</script>