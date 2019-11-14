<div class="category">
    <div class="form-group red_br col-1 float_l padd_0 padd_r_20">
        <span class="rbr" >' + rbr + '</span>
    </div>
    <div class="form-group categories {{ ($errors->has('name_category')) ? 'has-error' : '' }} col-9 float_l padd_0">
        <input name="category_id[]"  type="hidden" value="">
        <input name="name_category[]" onchange="inputChange(this, this.value)" class="category_input" type="text" value="{{ old('name_category') }}" placeholder="{{ __('questionnaire.add_category')}}" required >
        {!! ($errors->has('name_category') ? $errors->first('name_category', '<p class="text-danger">:message</p>') : '') !!}
    </div>
    <div class="form-group {{ ($errors->has('coefficient')) ? 'has-error' : '' }}  col-2 float_l padd_0 padd_l_20">
        <input name="coefficient[]" type="text" value="{{ old('coefficient') }}" placeholder="{{ __('questionnaire.coef') }}" >
        {!! ($errors->has('coefficient') ? $errors->first('coefficient', '<p class="text-danger">:message</p>') : '') !!}
    </div>
    <button type="button" class="collapsible option_dots"></button>
    <button type="button" class="content delete"  onclick="brisi_element(this)"><i class="far fa-trash-alt"></i></button>
    <div class="group_question">
        <div class="question">
            <div class="form-group red_br col-1 float_l padd_0 padd_r_20">
                <span class="rbr" >1</span>
            </div>
            <div class="form-group col-11 {{ ($errors->has('name_question'))  ? 'has-error' : '' }}">
                    <input name="question_id[]"  type="hidden" value="">
                    <input name="name_question[]" onchange="inputChange2( this, this.value )" class="question_input" type="text" value="{{ old('name_question') }}"  placeholder="{{ __('questionnaire.question')}}" required >
                    {!! ($errors->has('name_question') ? $errors->first('name_question', '<p class="text-danger">:message</p>') : '') !!}
            </div>
                <div class="form-group  offset-md-1 col-md-11 {{ ($errors->has('description'))  ? 'has-error' : '' }}">
                    <textarea  name="description[]" type="text" rows="3" placeholder="{{ __('basic.description')}}" required >
                        {{ old('description') }}
                    </textarea>
                        {!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
               </div>
               <div class="form-group offset-md-1 col-md-11">
                   <select class="form-control select_type" onchange="changeType(this, this.value )" name="type[]" required>
                   <option value="" selected disabled></option><option value="IN" >
                       {{ __('questionnaire.text_field') }}</option>
                    <option value="CB" >{{ __('questionnaire.multiple_choices') }}</option>
                    <option value="RB" >{{ __('questionnaire.single_choice')}}</option>
                </select>
            </div>
            <div class="group_answer">
                <div class="answer new 1">
                    <div class="form-group red_br offset-md-1 col-md-1 float_l padd_0 padd_r_20 padd_l_15">
                        <span class="rbr" >1</span>
                    </div>
                    <div class="form-group offset-md-2 col-md-10">
                        <input name="answer_id[]" type="hidden" value="">
                        <input name="answer[]" placeholder="odgovor" type="text" class="input_answer" />
                    </div>
                    <input name="question[]"  type="text" class="question_name" value="">
                </div>
            </div>
            <button type="button" onclick="addAnswer(this)" class="add_answer">+ ADD ANSWER</button>
            <button type="button" class="collapsible option_dots"></button>
            <button type="button" class="content delete"  onclick="brisi_element(this)">
            <i class="far fa-trash-alt"></i></button><input name="category[]" type="text" class="category_name" value=""></div></div><button type="button" onclick="addQuestion(this)" class="add_question">+ ADD QUESTION</button>
    </div>
</div>