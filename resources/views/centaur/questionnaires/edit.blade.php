<form id="quest_form" class="edit_form" accept-charset="UTF-8" role="form" method="post" action="{{ route('questionnaires.update', $questionnaire->id) }}" >
	<div class="modal-header">
		<a class="link_back" rel="modal:close">
			<img src="{{ URL::asset('icons/arrow_left2.png') }}" />
		</a>
		<input class="btn-submit new_qust" type="submit" value="{{ __('basic.publish')}}" />
		<!--<a href="#" class="btn-shedule" >
			<img class="img_shedule" src="{{ URL::asset('icons/clock.png') }}" alt="shedule" />
			<span>Shedule</span>
		</a>-->
		<h3 class="panel-title">@lang('questionnaire.edit_questionnaire')</h3>
	</div>
	<div class="modal-body new_questionnaire edit_q">
		<div class="aktivna form-group float_r">
			<label class="float_l container_radio">@lang('basic.inactive')  
				<input type="radio" name="status" value="0" {!! $questionnaire->status == '0' ? 'checked' : '' !!} />
				<span class="checkmark"></span>
			</label>
			<label class="float_l container_radio">@lang('basic.active')
				<input type="radio" name="status" value="1" {!! $questionnaire->status == '1' ? 'checked' : '' !!} />
				<span class="checkmark"></span>
			</label>
		</div>
		<div class="form-group q_name {{ ($errors->has('name'))  ? 'has-error' : '' }}">
			<label>@lang('basic.name')</label>
			<input name="name" type="text" maxlength="255" value="{{ $questionnaire->name }}" placeholder="Write your title here" autofocus required />
			{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="group_category">
			@php
				$i = 0;	
			@endphp
			@foreach($categories as $category)
				@php
					$i++;	
				@endphp
				<div class="category">
					<div class="form-group red_br col-1 float_l padd_0 padd_r_20">
						<span class="rbr" >{{ $i }}</span>
					</div>
					<div class="form-group categories {{ ($errors->has('name_category')) ? 'has-error' : '' }} col-9 float_l padd_0">
						<input name="category_id[]"  type="hidden" value="{{ $category->id }}" />
						<input name="name_category[]" onchange="inputChange( this.value )" class="category_input" type="text" maxlength="255"  class="form-control" value="{{ $category->name_category }}" placeholder="{{ __('questionnaire.add_category')}}" required />
						{!! ($errors->has('name_category') ? $errors->first('name_category', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group {{ ($errors->has('coefficient')) ? 'has-error' : '' }}  col-2 float_l padd_0 padd_l_20">
						<input name="coefficient[]" type="number" pattern="[0-9]+([.\,][0-9]+)?" value="{{  $category->coefficient }}" placeholder="{{ __('questionnaire.coef') }}" />
						{!! ($errors->has('coefficient') ? $errors->first('coefficient', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="group_question">
						@php
							$j = 0;	
						@endphp
						@foreach ($questions->where('category_id',  $category->id) as $question)
							@php
								$j++;	
							@endphp
							<div class="question">
								<div class="form-group red_br col-1 float_l padd_0 padd_r_20">
									<span class="rbr" >{{ $j }}</span>
								</div>
								<div class="form-group col-9 {{ ($errors->has('name_question'))  ? 'has-error' : '' }}">
									<input name="question_id[]"  type="hidden" value="{{ $question->id }}">
									<input name="name_question[]" type="text" maxlength="255" onchange="inputChange2( this, this.value )"  class="question_input" value="{{  $question->name_question }}" placeholder="{{ __('questionnaire.question')}}"  required >
									{!! ($errors->has('name_question') ? $errors->first('name_question', '<p class="text-danger">:message</p>') : '') !!}
								</div>
								<div class="form-group col-md-9">
									<select  name="type[]" class="form-control select_type" onchange="changeType(this, this.value )" required>
										<option value="IN" {!! $question->type == 'IN' ? 'selected' : '' !!} >@lang('questionnaire.text_field')</option>
										<option value="CB" {!! $question->type == 'CB' ? 'selected' : '' !!} >@lang('questionnaire.multiple_choices')</option>
										<option value="RB" {!! $question->type == 'RB' ? 'selected' : '' !!} >@lang('questionnaire.single_choice')</option>
									</select>
								</div>
								<div class="group_answer">
									@php
										$k=0;	
									@endphp
									@if($question->type != 'IN' && count($answers) >0)
										@foreach ($answers->where('question_id',  $question->id) as $answer)
											@php
												$k++;
											@endphp
											<div class="answer {{ $k }} ">
												<div class="form-group red_br offset-md-1 col-md-1 float_l padd_0 padd_r_20 padd_l_15">
													<span class="rbr" >{{ $k }}</span>
												</div>
												<div class="form-group offset-md-2 col-md-10" >
													<input name="answer_id[]" type="hidden" value="{{ $answer->id }}">
													<input name="answer[]" placeholder="odgovor" type="text" maxlength="100" value="{{ $answer->answer }}" class="input_answer" />
												</div>
												<input name="question[]"  type="text" maxlength="255" class="question_name" value="{{ $answer->answer }}" />
											</div>
										@endforeach
									@endif
									@php
										$l=1+$k;	
									@endphp
									<div class="answer new">
										<div class="form-group red_br offset-md-1 col-md-1 float_l padd_0 padd_r_20 padd_l_15">
											<span class="rbr" >{{ $l }}</span>
										</div>
										<div class="form-group offset-md-2 col-md-10" >
											<input name="answer_id[]" type="hidden" value="">
											<input name="answer[]" placeholder="odgovor" type="text" maxlength="100" value="" class="input_answer" />
										</div>
										<input name="question[]"  type="text" maxlength="255" class="question_name" value="">
									</div>
								</div>
								<button type="button" onclick="addAnswer(this)" class="add_answer">+ @lang('basic.add_answer')</button>
								<input name="category[]"  type="text" maxlength="255" class="category_name" value="{{ $category->name_category }}">
							</div>
						@endforeach
					</div>
					<button type="button" onclick="addQuestion(this)" class="add_question">+ @lang('basic.add_question')</button>
				</div>
			@endforeach
		</div>
		<span class="add_category" onclick="addCategory(this)" >+ @lang('basic.add_category')</span>
		{{ csrf_field() }}
		{{ method_field('PUT') }}
	</div>
</form>
<script>
	//cachedScript
	jQuery.cachedScript = function( url, options ) {
			// Allow user to set any option except for dataType, cache, and url
			options = $.extend( options || {}, {
				dataType: "script",
				cache: true,
				url: url
			});

			// Use $.ajax() since it is more flexible than $.getScript
			// Return the jqXHR object so we can chain callbacks
			return jQuery.ajax( options );
		};
		
		// Usage
		$.cachedScript( "/../js/questionnaire_show.js" ).done(function( script, textStatus ) {
		
		});
	
	$(function() {
		$('.select_type').each(function( index, element ){
			if($(element).val() == 'IN') {
				$(element).parent().siblings('.add_answer').hide();
			} else {
				$(element).parent().siblings('.add_answer').show();
				$(element).parent().siblings('.group_answer').show();
			}
		});
		
	});

	function addCategory (btn) {
		var rbr =  $( btn ).prev('.group_category').find('.category').last().find('.red_br .rbr').first().text();
		rbr ++;

		$(btn).parent().find('.group_category').append('<div class="category"><div class="form-group red_br col-1 float_l padd_0 padd_r_20"><span class="rbr" >' + rbr + '</span></div><div class="form-group categories {{ ($errors->has('name_category')) ? 'has-error' : '' }} col-9 float_l padd_0"><input name="name_category[]" onchange="inputChange(this, this.value)" class="category_input" type="text" maxlength="255" value="{{ old('name_category') }}" placeholder="{{ __('questionnaire.add_category')}}" required >{!! ($errors->has('name_category') ? $errors->first('name_category', '<p class="text-danger">:message</p>') : '') !!}</div><div class="form-group {{ ($errors->has('coefficient')) ? 'has-error' : '' }}  col-2 float_l padd_0 padd_l_20"><input name="coefficient[]" type="number" pattern="[0-9]+([.\,][0-9]+)?" value="{{ old('coefficient') }}" placeholder="{{ __('questionnaire.coef') }}" >{!! ($errors->has('coefficient') ? $errors->first('coefficient', '<p class="text-danger">:message</p>') : '') !!}</div><span><button type="button" class="collapsible option_dots"></button><button type="button" class="content delete"  onclick="brisi_element(this)"><i class="far fa-trash-alt"></i></button></span><div class="group_question"><div class="question"><div class="form-group red_br col-1 float_l padd_0 padd_r_20"><span class="rbr" >1</span></div><div class="form-group col-9 {{ ($errors->has('name_question'))  ? 'has-error' : '' }}"><input name="name_question[]" onchange="inputChange2( this, this.value )" class="question_input" type="text" maxlength="255" value="{{ old('name_question') }}"  placeholder="{{ __('questionnaire.question')}}" required >{!! ($errors->has('name_question') ? $errors->first('name_question', '<p class="text-danger">:message</p>') : '') !!}</div><div class="form-group col-9"><select class="form-control select_type" onchange="changeType(this, this.value )" name="type[]" required><option value="" selected disabled></option><option value="IN" >{{ __('questionnaire.text_field') }}</option><option value="CB" >{{ __('questionnaire.multiple_choices') }}</option><option value="RB" >{{ __('questionnaire.single_choice')}}</option></select></div><div class="group_answer"><div class="answer 1"><div class="form-group red_br offset-md-1 col-md-1 float_l padd_0 padd_r_20 padd_l_15"><span class="rbr" >1</span></div><div class="form-group offset-md-2 col-md-10"><input name="answer[]" placeholder="odgovor" type="text" maxlength="100" class="input_answer" /></div><input name="question[]"  type="text" maxlength="255" class="question_name" value=""></div></div><button type="button" onclick="addAnswer(this)" class="add_answer">+ ADD ANSWER</button><span><button type="button" class="collapsible option_dots"></button><button type="button" class="content delete"  onclick="brisi_element(this)"><i class="far fa-trash-alt"></i></button></span><input name="category[]" type="text" maxlength="255" class="category_name" value=""></div></div><button type="button" onclick="addQuestion(this)" class="add_question">+ ADD QUESTION</button></div></div>');
		$.getScript( "/../js/collaps.js" );
	}

	function addQuestion(btn) {
		var rbr = $( btn ).prev( '.group_question' ).find('.question').last().find('.red_br .rbr').first().text();
		rbr ++;
		var rbr2 = $( btn ).prev('.group_answer').find('.answer .red_br .rbr').last().text();
		rbr2 ++;

		$( btn ).prev( '.group_question' ).append('<div class="question"><div class="form-group red_br col-1 float_l padd_0 padd_r_20"><span class="rbr" >' + rbr + '</span></div><div class="form-group col-9 {{ ($errors->has('name_question'))  ? 'has-error' : '' }}"><input name="name_question[]"  onchange="inputChange2( this, this.value )" class="question_input" type="text" maxlength="255" value="{{ old('name_question') }}"  placeholder="{{ __('questionnaire.question')}}"  required >{!! ($errors->has('name_question') ? $errors->first('name_question', '<p class="text-danger">:message</p>') : '') !!}</div><div class="form-group col-md-9"><select class="form-control select_type" onchange="changeType(this, this.value )" name="type[]" required><option value="" selected disabled></option><option value="IN" >{{ __('questionnaire.text_field') }}</option><option value="CB" >{{ __('questionnaire.multiple_choices') }}</option><option value="RB" >{{ __('questionnaire.single_choice')}}</option></select></div><div class="group_answer"><div class="answer ' + rbr2 + '"><div class="form-group red_br offset-md-1 col-md-1 float_l padd_0 padd_r_20 padd_l_15"><span class="rbr" >' + rbr2 + '</span></div><div class="form-group offset-md-2 col-md-10"><input name="answer[]" placeholder="odgovor" type="text" maxlength="100" class="input_answer" /></div><input name="question[]"  type="text" maxlength="255" class="question_name" value=""></div></div><button type="button" onclick="addAnswer(this)" class="add_answer">+ ADD ANSWER</button><span><button type="button" class="collapsible option_dots"></button><button type="button" class="content delete" onclick="brisi_element(this)"><i class="far fa-trash-alt"></i></button></span><input name="category[]" type="text" maxlength="255" class="category_name" value=""></div></div>');
		
		var group_question = $( btn ).prev( '.group_question' );
		var category = $( group_question ).find('.category_name');

		var parent = $( btn ).parent();
		var catInput = $( parent ).find('.category_input').val();

		$(category).val(catInput);
		$.getScript( "/../js/collaps.js" );
	
	}

	function addAnswer(btn) {
		var rbr = $( btn ).prev('.group_answer').find('.answer .red_br .rbr').last().text();
		rbr ++;
		$( btn ).prev( '.group_answer ' ).append('<input name="answer_id[]" type="hidden" value=""><div class="answer ' + rbr + '"><div class="form-group red_br offset-md-1 col-md-1 float_l padd_0 padd_r_20 padd_l_15"><span class="rbr" >' + rbr + '</span></div><div class="form-group offset-md-2 col-md-10"><input name="answer_id[]" type="hidden" value=""><input name="answer[]" placeholder="odgovor" type="text" maxlength="100" class="input_answer" /></div><input name="question[]" type="text" maxlength="255" class="question_name" value=""></div>');

		var group_answer = $( btn ).prev( '.group_answer' );
		var question = $( group_answer ).find('.question_name');

		var parent = $( btn ).parent();
		var catInput = $( parent ).find('.question_input').val();

		$(question).val(catInput);
		$.getScript( "/../js/collaps.js" );
		
	}

	function brisi_element ( element ) {
		if($( element ).parent().parent().hasClass('category')) {
			$( element ).parent().parent().remove();
			$( '.group_category .category' ).each( function( index, element2 ){
				$(element2).find('.red_br .rbr').first().text(index + 1);
			});
		}
		if($( element ).parent().parent().hasClass('question')) {
			var sibl = $( element ).parent().siblings();
			$( element ).parent().parent().remove();
			$(sibl).each(function(index, element2){
				$(element2).find('.red_br .rbr').text(index+1);
			});
		}
	}

	function changeType(element, value) {
		if($( element).val() == 'RB' || $( element).val() == 'CB' ) {
			$(element).parent().siblings('.group_answer').show();
			$(element).parent().siblings('.add_answer').show();
			$(element).parent().siblings('.group_answer').find('.answer').show();

		} else {
			$(element).parent().siblings('.group_answer').hide();
			$(element).parent().siblings('.add_answer').hide();
			$(element).parent().siblings('.group_answer').find('.answer:not(".1")').remove();
			$(element).parent().siblings('.group_answer').find('.input_answer').val('');
		}
	}

	function inputChange ( element, input ) {
		var parent = $( element ).parent();
		var group_question = $( parent ).siblings('.group_question');
		var category = $( group_question ).find('.category_name');
		category.val(input);
	} 

	function inputChange2 ( element, input ) {
		var parent = $( element ).parent();
		var group_answer = $( parent ).siblings('.group_answer');
		var question = $( group_answer ).find('.question_name');
		question.val(input);
	} 
</script>