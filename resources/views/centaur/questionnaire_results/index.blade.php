<div class="modal-header results">
    <a class="link_back" rel="modal:close">
        <img src="{{ URL::asset('icons/arrow_left2.png') }}" />
    </a>
    <h2 class="col-6">@lang('questionnaire.results'): {{ $questionnaire->name }}</h2>
</div>
<div class="modal-body results">
    <p id="user" hidden >{{ Sentinel::getUser()->first_name . ' ' . Sentinel::getUser()->last_name  }}</p>
    @php $i = 0; $j = 0; @endphp
    <section class="pitanja">
        <div class="anketa" id="anketa">
            @foreach($evaluationCategories as $category)
                @php $i++; @endphp
                <h4 id="{{ $category->name_category }}"><span class="rbr">{{ $i }}</span>{{ $category->name_category }}</h4>
                @foreach($evaluationQuestions->where('category_id', $category->id) as $question)
                    @php $j++;
                         $countResults = 0;
                         $countAnswers = 0;
                    @endphp
                    <div class="pitanje">
                        <p><span class="rbr">{{ $j }}</span> {{ $question->name_question }}</p>
                    </div>
                    <span class="ocj">
                        @foreach($results->where('question_id', $question->id ) as $result)
                            @if($question->type == 'IN')
                                <div class="answers_input">
                                    <div class="answer_input">
                                        <p class="fl_name">{{ $result->employee->user['first_name'] . ' ' . $result->employee->user['last_name'] }}</p>
                                        <p>{{ $result->answer }}</p>
                                    </div>
                                </div>
                            @endif
                        @endforeach   
                        @foreach($evaluationAnswers->where('question_id', $question->id) as $answer)
                            @if( $question->type != 'IN' && $answer->question_id == $question->id)
                                @if( $answer->count > 0)
                                    <label class="container_radio count">{{ $answer->answer }}
                                        <span class="checkmark count_results">
                                            {{ $answer->count    . '%' }}
                                        </span>
                                    </label>
                                @endif
                            @endif
                        @endforeach
                    </span>
                @endforeach
            @endforeach
        </div>
    </section>
</div>
<script >$(function(){$.getScript( 'js/questionnaire_show.js');});</script>






