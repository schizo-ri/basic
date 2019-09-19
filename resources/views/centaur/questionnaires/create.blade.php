
<form id="quest_form" accept-charset="UTF-8" role="form" method="post" action="{{ route('questionnaires.store') }}" >
	<div class="modal-header">
		<a class="link_back" rel="modal:close">
			<img src="{{ URL::asset('icons/arrow_left2.png') }}" />
		</a>
		<input class="btn-submit new_qust" type="submit" value="{{ __('basic.publish')}}">
		<a href="#" class="btn-shedule" >
			<img class="img_shedule" src="{{ URL::asset('icons/clock.png') }}" alt="shedule" />
			<span>Shedule</span>
		</a>
		<h3 class="panel-title">@lang('questionnaire.add_questionnaire')</h3>
	</div>
	<div class="modal-body new_questionnaire">
			<div class="aktivna form-group float_r">
				<label class="float_l container_radio">@lang('basic.inactive')  
					<input type="radio" name="status" value="0" checked />
					<span class="checkmark"></span>
				</label>
				<label class="float_l container_radio">@lang('basic.active')
					<input type="radio" name="status" value="1" />
					<span class="checkmark"></span>
				</label>
			</div>
		<div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
			<label>@lang('basic.name')</label>
			<input name="name" type="text" value="{{ old('name') }}" placeholder="Write your title here" autofocus required />
			{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="group_category">
			<div class="category">
				<div class="form-group red_br {{ ($errors->has('name_category')) ? 'has-error' : '' }} col-1 float_l padd_0 padd_r_20">
					<span class="rbr" >1</span>
				</div>
				<div class="form-group categories {{ ($errors->has('name_category')) ? 'has-error' : '' }} col-9 float_l padd_0">
					<input name="name_category[]" onchange="inputChange( this, this.value )" class="category_input" type="text" value="{{ old('name_category') }}" placeholder="{{ __('questionnaire.add_category')}}" required >
					{!! ($errors->has('name_category') ? $errors->first('name_category', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<div class="form-group {{ ($errors->has('coefficient')) ? 'has-error' : '' }}  col-2 float_l padd_0 padd_l_20">
					<input name="coefficient[]" type="text" value="{{ old('coefficient') }}" placeholder="{{ __('questionnaire.coef') }}" >
					{!! ($errors->has('coefficient') ? $errors->first('coefficient', '<p class="text-danger">:message</p>') : '') !!}
				</div>
				<button type="button" class="collapsible option_dots"></button>
				<button type="button" class="content delete"  onclick="brisi_element(this)">
					<i class="far fa-trash-alt"></i>
				</button>
				<div class="group_question">
					<div class="question">
						<div class="form-group red_br col-1 float_l padd_0 padd_r_20">
							<span class="rbr" >1</span>
						</div>
						<div class="form-group col-11 {{ ($errors->has('name_question'))  ? 'has-error' : '' }}">
							<input name="name_question[]" onchange="inputChange2( this, this.value )" class="question_input" type="text" value="{{ old('name_question') }}"  placeholder="{{ __('questionnaire.question')}}"  required >
							{!! ($errors->has('name_question') ? $errors->first('name_question', '<p class="text-danger">:message</p>') : '') !!}
						</div>
						<div class="form-group offset-md-1 col-md-11">
							<select class="form-control select_type" onchange="changeType(this, this.value )" name="type[]"  required>
								<option value="" selected disabled></option>
								<option value="IN" >@lang('questionnaire.text_field')</option>
								<option value="RB" >@lang('questionnaire.single_choice')</option>
								<option value="CB" >@lang('questionnaire.multiple_choices')</option>
							</select>
						</div>
						<div class="group_answer">
							@php
								$k=1;	
							@endphp
							<div class="answer {{ $k }} ">
								<div class="form-group red_br offset-md-1 col-md-1 float_l padd_0 padd_r_20 padd_l_15">
									<span class="rbr" >{{ $k }}</span>
								</div>
								<div class="form-group offset-md-2 col-md-10" >
									<input name="answer[]" placeholder="odgovor" type="text" class="input_answer" />
								</div>
								<input name="question[]"  type="text" class="question_name" value="">
							</div>
						</div>
						<button type="button" onclick="addAnswer(this)" class="add_answer">+ ADD ANSWER</button>
						
						<button type="button" class="collapsible option_dots"></button>
						<button type="button" class="content delete"  onclick="brisi_element(this)">
							<i class="far fa-trash-alt"></i>
						</button>
						<input name="category[]"  type="text" class="category_name" value="">
					</div>
				</div>
				<button type="button" onclick="addQuestion(this)" class="add_question">+ ADD QUESTION</button>
			</div>
		</div>
		<span class="add_category" onclick="addCategory(this)" >+ ADD CATEGORY</span>
		{{ csrf_field() }}
	</div>
</form>
<script>
	$(function() {
		// dodaje klasu na modal i diže skripte
		$('.modal').addClass('modal_questionnaire');
		$.getScript( 'js/questionnaire_show.js');
		$.getScript( 'js/collaps.js');
	
	});

	function addCategory (btn) {
		var rbr =  $( btn ).prev('.group_category').find('.category').last().find('.red_br .rbr').first().text();
		rbr ++;

		$(btn).parent().find('.group_category').append('<div class="category"><div class="form-group red_br col-1 float_l padd_0 padd_r_20"><span class="rbr" >' + rbr + '</span></div><div class="form-group categories {{ ($errors->has('name_category')) ? 'has-error' : '' }} col-9 float_l padd_0"><input name="name_category[]" onchange="inputChange(this, this.value)" class="category_input" type="text" value="{{ old('name_category') }}" placeholder="{{ __('questionnaire.add_category')}}" required >{!! ($errors->has('name_category') ? $errors->first('name_category', '<p class="text-danger">:message</p>') : '') !!}</div><div class="form-group {{ ($errors->has('coefficient')) ? 'has-error' : '' }}  col-2 float_l padd_0 padd_l_20"><input name="coefficient[]" type="text" value="{{ old('coefficient') }}" placeholder="{{ __('questionnaire.coef') }}" >{!! ($errors->has('coefficient') ? $errors->first('coefficient', '<p class="text-danger">:message</p>') : '') !!}</div><button type="button" class="collapsible option_dots"></button><button type="button" class="content delete"  onclick="brisi_element(this)"><i class="far fa-trash-alt"></i></button><div class="group_question"><div class="question"><div class="form-group red_br col-1 float_l padd_0 padd_r_20"><span class="rbr" >1</span></div><div class="form-group col-11 {{ ($errors->has('name_question'))  ? 'has-error' : '' }}"><input name="name_question[]" onchange="inputChange2( this, this.value )" class="question_input" type="text" value="{{ old('name_question') }}"  placeholder="{{ __('questionnaire.question')}}" required >{!! ($errors->has('name_question') ? $errors->first('name_question', '<p class="text-danger">:message</p>') : '') !!}</div><div class="form-group offset-md-1 col-md-11"><select class="form-control select_type" onchange="changeType(this, this.value )" name="type[]" required><option value="" selected disabled></option><option value="IN" >{{ __('questionnaire.text_field') }}</option><option value="CB" >{{ __('questionnaire.multiple_choices') }}</option><option value="RB" >{{ __('questionnaire.single_choice')}}</option></select></div><div class="group_answer"><div class="answer 1"><div class="form-group red_br offset-md-1 col-md-1 float_l padd_0 padd_r_20 padd_l_15"><span class="rbr" >1</span></div><div class="form-group offset-md-2 col-md-10"><input name="answer[]" placeholder="odgovor" type="text" class="input_answer" /></div><input name="question[]"  type="text" class="question_name" value=""></div></div><button type="button" onclick="addAnswer(this)" class="add_answer">+ ADD ANSWER</button><button type="button" class="collapsible option_dots"></button><button type="button" class="content delete"  onclick="brisi_element(this)"><i class="far fa-trash-alt"></i></button><input name="category[]" type="text" class="category_name" value=""></div></div><button type="button" onclick="addQuestion(this)" class="add_question">+ ADD QUESTION</button></div></div>');
		$.getScript( 'js/collaps.js');
		$.getScript( 'js/questionnaire_show.js');
	}
	
	function addQuestion(btn) {
		var rbr = $( btn ).prev( '.group_question' ).find('.question').last().find('.red_br .rbr').first().text();
		rbr ++;
		var rbr2 = $( btn ).prev('.group_answer').find('.answer .red_br .rbr').last().text();
		rbr2 ++;

		$( btn ).prev( '.group_question' ).append('<div class="question"><div class="form-group red_br col-1 float_l padd_0 padd_r_20"><span class="rbr" >' + rbr + '</span></div><div class="form-group col-11 {{ ($errors->has('name_question'))  ? 'has-error' : '' }}"><input name="name_question[]"  onchange="inputChange2( this, this.value )" class="question_input" type="text" value="{{ old('name_question') }}"  placeholder="{{ __('questionnaire.question')}}"  required >{!! ($errors->has('name_question') ? $errors->first('name_question', '<p class="text-danger">:message</p>') : '') !!}</div><div class="form-group offset-md-1 col-md-11"><select class="form-control select_type" onchange="changeType(this, this.value )" name="type[]" required><option value="" selected disabled></option><option value="IN" >{{ __('questionnaire.text_field') }}</option><option value="CB" >{{ __('questionnaire.multiple_choices') }}</option><option value="RB" >{{ __('questionnaire.single_choice')}}</option></select></div><div class="group_answer"><div class="answer ' + rbr2 + '"><div class="form-group red_br offset-md-1 col-md-1 float_l padd_0 padd_r_20 padd_l_15"><span class="rbr" >' + rbr2 + '</span></div><div class="form-group offset-md-2 col-md-10"><input name="answer[]" placeholder="odgovor" type="text" class="input_answer" /></div><input name="question[]"  type="text" class="question_name" value=""></div></div><button type="button" onclick="addAnswer(this)" class="add_answer">+ ADD ANSWER</button><button type="button" class="collapsible option_dots"></button><button type="button" class="content delete" onclick="brisi_element(this)"><i class="far fa-trash-alt"></i></button><input name="category[]" type="text" class="category_name" value=""></div></div>');

		var group_question = $( btn ).prev( '.group_question' );
		var category = $( group_question ).find('.category_name');

		var parent = $( btn ).parent();
		var catInput = $( parent ).find('.category_input').val();

		$(category).val(catInput);
		$.getScript( 'js/collaps.js');
		$.getScript( 'js/questionnaire_show.js');
	}

	function addAnswer(btn) {
		var rbr = $( btn ).prev('.group_answer').find('.answer .red_br .rbr').last().text();
		rbr ++;
		$( btn ).prev( '.group_answer' ).append('<div class="answer ' + rbr + '"><div class="form-group red_br offset-md-1 col-md-1 float_l padd_0 padd_r_20 padd_l_15"><span class="rbr" >' + rbr + '</span></div><div class="form-group offset-md-2 col-md-10"><input name="answer[]" placeholder="odgovor" type="text" class="input_answer" /></div><input name="question[]"  type="text" class="question_name" value=""></div>');

		var group_answer = $( btn ).prev( '.group_answer' );
		var question = $( group_answer ).find('.question_name');

		var parent = $( btn ).parent();
		var catInput = $( parent ).find('.question_input').val();
			console.log(catInput);
			console.log($(question));
		$(question).val(catInput);
		$.getScript( 'js/collaps.js');
		$.getScript( 'js/questionnaire_show.js');
	}

	function brisi_element ( element ) {
		if($( element ).parent().hasClass('category')) {
			$( element ).parent().remove();
			$( '.group_category .category' ).each( function( index, element2 ){
				$(element2).find('.red_br .rbr').first().text(index + 1);
			});
		}
		if($( element ).parent().hasClass('question')) {
			var sibl = $( element ).parent().siblings();
			$( element ).parent().remove();
			$(sibl).each(function(index, element2){
				$(element2).find('.red_br .rbr').text(index+1);
			});
			
		}
	}
	
	function changeType(element, value) {
		if($( element).val() == 'RB' || $( element).val() == 'CB' ) {
			$(element).parent().siblings('.group_answer').show();
			$(element).parent().siblings('.add_answer').show();

		} else {
			$(element).parent().siblings('.group_answer').hide();
			$(element).parent().siblings('.add_answer').hide();
			$(element).parent().siblings('.group_answer').find('.answer:not(".1")').remove();
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