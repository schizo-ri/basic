<form accept-charset="UTF-8" role="form" method="post" action="{{ route('questionnaire_results.store') }}" class="modal-open">
	<div class="modal-header">
		<a class="link_back" rel="modal:close">
			<img src="{{ URL::asset('icons/arrow_left2.png') }}" />
		</a>
		@if(Sentinel::getUser()->hasAccess(['questionnaire_results.view']) || in_array('questionnaire_results.view', $permission_dep) )
			<a href="{{ route('questionnaire_results.index', ['id' => $questionnaire->id]) }}" class="btn-statistic"  rel="modal:open">
				<img class="img_statistic" src="{{ URL::asset('icons/curve-arrow_right.png') }}" alt="all notice" />
				<span>@lang('questionnaire.results')</span>
			</a>
		@endif
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
				<span class="progress"></span>
			</span>
			<span class="progress_val"></span>
		</div>
		<h2 class="col-6 float_l">{{ $questionnaire->name }}</h2>
	</div>
	<div class="modal-body">
		<p id="user" hidden >{{ Sentinel::getUser()->first_name . ' ' . Sentinel::getUser()->last_name  }}</p>
		<input name="questionnaire_id" type="hidden" value ="{{ $questionnaire->id }}" /><span id="countCheched"></span>
		@php $i = 0; $j = 0; @endphp
		<section class="pitanja">
			<div class="anketa" id="anketa">
				@foreach($evaluationCategories as $category)
					@php $i++; @endphp
					<h4 id="{{ $category->name_category }}"><span class="rbr">{{ $i }}</span><span class="ctg_name">{{ $category->name_category }}</span></h4>
					<input name="group_id[{{ $category->id }}]" type="hidden" value="{{ $category->id }}" id="group_id2" />
					@foreach($evaluationQuestions->where('category_id', $category->id) as $question)
						@php $j++; @endphp
						<div class="pitanje">
							<input name="question_id[{{ $question->id }}]" type="hidden" value="{{ $question->id }}"  id="group_id1" />
							<p><span class="rbr">{{ $j }}</span><span class="ctg_name">{{ $question->name_question }}</span></p>
						<!--	<p class="opis">{{ $question->description }}</p>	-->
						</div>
						<span class="ocj">
							@if($question->type == 'RB' || $question->type == 'CB')
								@foreach($evaluationAnswers->where('question_id', $question->id) as $answer)
									@if($question->type == 'RB')
										<label class="container_radio">{{ $answer->answer }}
											<input type="radio" name="rating[{{ $question->id }}]" value="{{ $answer->id }}" id="myRadio2{{ $category->id }}" required />
											<span class="checkmark radio"></span>
										</label>
									@endif
									@if($question->type == 'CB')
										<label class="container_radio">{{ $answer->answer }}
											<input type="checkbox" name="rating[{{ $question->id }}_{{$answer->id }}]" value="{{ $answer->id }}" id="myRadio2{{ $category->id }}" />
											<span class="checkmark"></span>
										</label>
									@endif
								@endforeach
							@endif
							@if($question->type == 'IN')
								<textarea type="text" class="input_answer" name="rating[{{ $question->id }}]" rows="3" id="myRadio2{{ $category->id }}" required></textarea>
							@endif
						</span>
					@endforeach
				@endforeach
			</div>
		</section>
		<input name="_token" value="{{ csrf_token() }}" type="hidden">
		<input class="btn-submit fill" type="submit" value="{{ __('basic.save')}}">
	</div>
</form>
<script >
	$(function(){
		$.getScript( 'js/questionnaire_show.js');
	});
</script>