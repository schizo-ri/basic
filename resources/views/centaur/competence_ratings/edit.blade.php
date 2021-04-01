<div class="modal-header">
    <h3 class="panel-title">@lang('basic.edit_category')</h3>
</div>
<div class="modal-body">
    <form accept-charset="UTF-8" role="form" method="post" action="{{ route('competence_ratings.update', $competenceRating->id) }}">
        <div class="form-group {{ ($errors->has('rating'))  ? 'has-error' : '' }}">
            <label>@lang('questionnaire.rating')</label>
                <input class="form-control" name="rating" type="number" step="1" min="0" max="100" value="{{ $competenceRating->rating }}" required/>    
                {!! ($errors->has('rating') ? $errors->first('rating', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        <div class="form-group {{ ($errors->has('description'))  ? 'has-error' : '' }}">
            <label>@lang('basic.description')</label>
            <input name="description" type="text" class="form-control" value="{{ $competenceRating->description }}" maxlength="100" required>
            {!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        @method('PUT')
        @csrf
        <input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
    </form>
</div>