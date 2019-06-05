@extends('Centaur::layout')

@section('title', 'Event')

@section('content')
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Event</h3>
            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="post" action="{{ route('events.store') }}">
					<div class="form-group {{ ($errors->has('title')) ? 'has-error' : '' }}">
						<label>Event title:</label>
						<input name="title" type="text" class="form-control" required>
						{!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group datum {{ ($errors->has('prezime')) ? 'has-error' : '' }}">
						<label>Event date:</label>
						<input name="date" type="date" class="form-control" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" required>
						{!! ($errors->has('prezime') ? $errors->first('prezime', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<label class="time_label">Event time</label>
					<div class="form-group time {{ ($errors->has('time1')) ? 'has-error' : '' }}">
						<input name="time1" class="form-control" type="time" value="08:00" required />
						{!! ($errors->has('time1') ? $errors->first('time1', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group span">
						<span>do</span>
					</div>
					<div class="form-group time {{ ($errors->has('time2')) ? 'has-error' : '' }}">
						<input name="time2" class="form-control" type="time" value="08:00" required />
						{!! ($errors->has('time2') ? $errors->first('time2', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					<div class="form-group description {{ ($errors->has('description')) ? 'has-error' : '' }}">
						<label>Description</label>
						<textarea name="description" class="form-control" type="text" required ></textarea>
						{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
					</div>
					
					{{ csrf_field() }}
					<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
				</form>
            </div>
        </div>
    </div>
</div>
@stop