@php use App\Http\Controllers\QuestionnaireController; @endphp
	<div class="modal-header results">
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
		<h2 class="col-12 ">{{ $questionnaire->name }}</h2>
    </div>
	<div class="modal-body results">
		<span id="countCheched"></span>
		@php $i = 0; $j = 0; @endphp
		<section class="pitanja">
			<div class="anketa" id="anketa">
				@foreach($evaluationCategories as $category)
					@php $i++; @endphp
					<h4 id="{{ $category->name_category }}"><span class="rbr">{{ $i }}</span>{{ $category->name_category }}</h4>
					@foreach($evaluationQuestions->where('category_id', $category->id) as $question)
						@php $j++; @endphp
						<div class="pitanje">
							<p><span class="rbr">{{ $j }}</span> {{ $question->name_question }}</p>
						<!--	<p class="opis">{{ $question->description }}</p>	-->
						</div>
						<span class="ocj">
                            @if($question->type == 'RB' || $question->type == 'CB')
								@foreach($evaluationAnswers->where('question_id', $question->id) as $answer)
									@if($question->type == 'RB')
										<label class="container_radio">{{ $answer->answer }}
											<input type="radio" name="rating[{{ $question->id }}]" value="{{ $answer->id }}" id="myRadio2{{ $category->id }}" {!! $empl_results->where('question_id',  $question->id)->where('answer_id',$answer->id)->first() ? 'checked' : ''  !!} />
											<span class="checkmark radio"></span>
										</label>
									@endif
									@if($question->type == 'CB')
										<label class="container_radio">{{ $answer->answer }}
											<input type="checkbox" name="rating[{{ $question->id }}_{{$answer->id }}]" value="{{ $answer->id }}" id="myRadio2{{ $category->id }}" {!! $empl_results->where('question_id', $question->id)->where('answer_id',$answer->id)->first() ? 'checked' : ''  !!} />
											<span class="checkmark"></span>
										</label>
									@endif
								@endforeach
							@endif
							@if($question->type == 'IN')
								<textarea type="text" class="input_answer" name="rating[{{ $question->id }}]" rows="3" id="myRadio2{{ $category->id }}" >{{ $empl_results->where('question_id',  $question->id)->first()['answer'] }}</textarea>
							@endif
						</span>
					@endforeach
				@endforeach
			</div>
		</section>
	</div>
<script >$(function(){$.getScript( 'js/questionnaire_show.js');});</script>