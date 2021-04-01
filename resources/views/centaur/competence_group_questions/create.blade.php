<div class="modal-header">
    <h3 class="panel-title">@lang('basic.new_category')</h3>
</div>
<div class="modal-body">
    <form accept-charset="UTF-8" role="form" method="post" action="{{ route('competence_group_questions.store') }}">
        <input type="hidden" name="competence_id" value="{!! $competence_id ? $competence_id : null !!}">
        <div class="form-group {{ ($errors->has('name'))  ? 'has-error' : '' }}">
            <label>@lang('basic.name')</label>
            <input name="name" type="text" class="form-control" value="{{ old('name') }}" maxlength="100" required>
            {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group clear_l {{ ($errors->has('description')) ? 'has-error' : '' }}">
			<label>@lang('basic.description')</label>
			<textarea name="description" class="form-control" type="text" maxlength="65535" >{{ old('description') }}</textarea>
			{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
		</div>
        <div class="form-group {{ ($errors->has('coefficient'))  ? 'has-error' : '' }}">
            <label>@lang('questionnaire.coef')</label>
            <input name="coefficient" type="number" class="form-control" step="0.01" {{-- min="0" max="1"  --}} value="{{ old('coefficient') }}" maxlength="100" required>
            {!! ($errors->has('coefficient') ? $errors->first('coefficient', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <h5>@lang('questionnaire.questions')</h5>
        <div class="form-group">
            @for ($i = 1; $i <= 5; $i++)
                <span>{{ $i }}.</span>
                <div class="overflow_hidd rating_group">
                    <div>
                        <input name="q_name[]" type="text" class="form-control" maxlength="191" placeholder="Pitanje" >
                    </div>
                    <div>
                        <textarea name="q_description[]" class="form-control" type="text" maxlength="65535" placeholder="opis pitanja"></textarea>
                    </div>
                    <div>
                        <input name="q_rating[]" type="number" min="1" max="50" step="1" class="form-control" placeholder="Ocjena" value="5">
                    </div>
                </div>
            @endfor
            <span class="add_rating">Dodaj Ocjenu</span>
        </div>
        {{ csrf_field() }}
        <input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
    </form>
</div>
<script>
    $('.add_rating').on('click',function(){
        var num_rating = $('.rating_group').length;
        console.log(num_rating);
        $( this ).before('<span>'+(num_rating+1) + '.</span><div class="overflow_hidd rating_group"><div><input name="q_name[]" type="text" class="form-control" maxlength="191" ></div><div><textarea name="q_description[]" class="form-control" type="text" maxlength="65535" ></textarea></div><div><input name="q_rating[]" type="number" min="1" max="50" step="1" class="form-control" placeholder="Ocjena" value="5"></div></div>');
    });
</script>

