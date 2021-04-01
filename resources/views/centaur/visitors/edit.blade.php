<div class="modal-header">
	<h3 class="panel-title">Ispravi posjetitelja</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" class="" method="post" action="{{ route('visitors.update', $visitor->id) }}">
		<div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }} ">
			<input class="form-control" placeholder="Ime" name="first_name" type="text" maxlength="20" value="{{ $visitor->first_name }}" autofocus/>
			{!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }} ">
			<input class="form-control" placeholder="Prezime" name="last_name" type="text" maxlength="20" value="{{ $visitor->last_name }}" />
			{!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
			<input class="form-control" placeholder="E-mail" name="email" type="text" maxlength="50" value="{{ $visitor->email }}">
			{!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<div class="form-group {{ ($errors->has('company')) ? 'has-error' : '' }} ">
			<input class="form-control" placeholder="Tvrtka" name="company" type="text" maxlength="50" value="{{ $visitor->company }}">
			{!! ($errors->has('company') ? $errors->first('company', '<p class="text-danger">:message</p>') : '') !!}
		</div>
		<input type="hidden" name="card_id" value="{{ $visitor->card_id}}" >
		<div class="form-group">
			<label>Povrat kartice</label>
			<input class="form-control" name="returned" type="date" value="{{ $visitor->returned }}" >
		</div>
		<input name="accept" type="hidden" value="1" >
		{{ method_field('PUT') }}
		{{ csrf_field() }}
		<input class="btn-submit btn_submit_reg" type="submit" value="Ispravi"> 
	</form>
</div>