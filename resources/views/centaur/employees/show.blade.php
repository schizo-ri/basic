<header >
	<a class="back" rel="modal:close">
		<span class="arrow_left_wh" />
	</a>
	<a class="float_right" href="{{ route('show_print',$employee->id) }}" target="_blank">
		<i class="fas fa-print"></i>
	</a>
	<div class="user_data">
		<h4 class="panel-title">@lang('basic.employee')</h4>
	</div>
</header>
<main class="">
	<div class="basic_info">
		<h4>Osobni podaci</h4>
		<label class="show_empl_label" for="">@lang('basic.fl_name')</label>
			<p class="show_empl_p" >{{  $employee->user->first_name . ' ' .  $employee->user->last_name }}</p>
		<label class="show_empl_label" for="">@lang('basic.b_day')</label>
			<p class="show_empl_p" >{{ date('d.m.Y',strtotime($employee->b_day)) }}</p>
		<label class="show_empl_label" for="">@lang('basic.b_place')</label>
			<p class="show_empl_p" >{{  $employee->b_place  }}</p>
		<label class="show_empl_label" for="">@lang('basic.father_name')</label>
			<p class="show_empl_p" >{{  $employee->father_name  }}</p>
		<label class="show_empl_label" for="">@lang('basic.mather_name')</label>
			<p class="show_empl_p" >{{  $employee->mather_name  }}</p>
		<label class="show_empl_label" for="">@lang('basic.maiden_name')</label>
			<p class="show_empl_p" >{{  $employee->maiden_name  }}</p>
		<label class="show_empl_label" for="">OIB</label>
			<p class="show_empl_p" >{{  strval($employee->oib)}}</p>
		<label class="show_empl_label" for="">@lang('basic.oi')</label>
			<p class="show_empl_p" >{{ $employee->oi }}</p>
		<label class="show_empl_label" for="">@lang('basic.oi_expiry')</label>
			<p class="show_empl_p" >{{ date('d.m.Y',strtotime($employee->oi_expiry)) }}</p>
		<label class="show_empl_label" for="">@lang('basic.marital')</label>
			<p class="show_empl_p" >{!! $employee->marital == 'yes' ? 'Oženjen / udata' : '' !!}</p>
		<br>
	</div>
	<div class="basic_info">
		<h4>Kontakt</h4>

		<label class="show_empl_label" for="">@lang('basic.priv_email')</label>
		<p class="show_empl_p" >{{ $employee->priv_email }}</p>
		<label class="show_empl_label" for="">E-mail</label>
			<p class="show_empl_p" >{{ $employee->email }}</p>
		<label class="show_empl_label" for="">@lang('basic.priv_mobile')</label>
			<p class="show_empl_p" >{{ $employee->priv_mobile }}</p>
		<label class="show_empl_label" for="">@lang('basic.mobile')</label>
			<p class="show_empl_p" >{{ $employee->mobile }}</p>
		<br>
		<label class="show_empl_label" for="">Prebivalište adresa</label>
			<p class="show_empl_p" >{{ $employee->prebiv_adresa }}</p>
		<label class="show_empl_label" for="">Prebivalište grad</label>
			<p class="show_empl_p" >{{ $employee->prebiv_grad }}</p>
		<label class="show_empl_label" for="">Boravište adresa</label>
			<p class="show_empl_p" >{{ $employee->borav_adresa }}</p>
		<label class="show_empl_label" for="">Boravište grad</label>
			<p class="show_empl_p" >{{ $employee->borav_grad }}</p>
		<br>
	</div>
	<div class="basic_info">
		<h4>Podaci o zaposlenju</h4>
		<label class="show_empl_label" for="">@lang('basic.reg_date')</label>
			<p class="show_empl_p" >{{ date('d.m.Y',strtotime($employee->reg_date)) }}</p>
		<label class="show_empl_label" for="">@lang('basic.work')</label>
			<p class="show_empl_p" >{{ $employee->work->name }}</p>
		<label class="show_empl_label" for="">@lang('basic.metier')</label>
			<p class="show_empl_p" >{{ $employee->title }}</p>
		<label class="show_empl_label" for="">@lang('basic.qualifications')</label>
			<p class="show_empl_p" >{{ $employee->qualifications }}</p>
		
		<label class="show_empl_label" for="">Staž kod prošlog poslodavca (godina-mjeseci-dana):</label>
			<p class="show_empl_p" >{{ $employee->years_service }}</p>

		<label class="show_empl_label" for="">@lang('basic.probation')</label>
			<p class="show_empl_p" >{{ $employee->probation }}</p>
		<label class="show_empl_label" for="">Prekid radnog odnosa više od 8 dana</label>
			<p class="show_empl_p" >{!! $employee->termination_service == 1 ? 'DA' : 'NE' !!}</p>
		<label class="show_empl_label" for="">Prvo zaposlenje</label>
			<p class="show_empl_p" >{!! $employee->first_job == 1 ? 'DA' : 'NE' !!}</p>
		@if(Sentinel::inRole('view_efc') || Sentinel::inRole('uprava') )
			<label class="show_empl_label" for="">Efektivna cijena sata:</label>
				<p class="show_empl_p" >{{ $employee->effective_cost }} Kn</p>
			<label class="show_empl_label" for="">Brutto godišnja plaća:</label>
				<p class="show_empl_p" >{{ $employee->brutto }} Kn</p>
		@endif
		<label class="show_empl_label" for="">@lang('basic.lijecn_pregled')</label>
			<p class="show_empl_p" >{{ date('d.m.Y',strtotime($employee->lijecn_pregled)) }}</p>
		<label class="show_empl_label" for="">@lang('basic.znr')</label>
			<p class="show_empl_p" >{{ date('d.m.Y',strtotime($employee->znr)) }}</p>
		<br>
	</div>
	<div class="basic_info">
		<h4>Ostalo</h4>
		<label class="show_empl_label" for="">@lang('basic.size')</label>
			<p class="show_empl_p" >{{ $employee->size }}</p>
		<label class="show_empl_label" for="">@lang('basic.shoe_size')</label>
			<p class="show_empl_p" >{{ $employee->shoe_size }}</p>
		<label class="show_empl_label" for="">Obračun prekovremenih kao:</label>
			<p class="show_empl_p" >{!! $employee->days_off == 1 ? 'Slobodni dani' : 'Isplata' !!}</p>
		<label class="show_empl_label" for="">Djelatnik je stranac</label>
			<p class="show_empl_p" >{!! $employee->stranger == 1 ? 'DA' : 'NE' !!}</p>
		<label class="show_empl_label" for="">Datum isteka dozvole boravka u RH:</label>
			<p class="show_empl_p" >{!! $employee->permission_date ? date('d.m.Y',strtotime($employee->permission_date)) : '' !!}<p>
		<br>
		<label class="show_empl_label" for="">@lang('basic.comment')</label>
			<p class="show_empl_p" >{{ $employee->comment }}</p>
		@if (count( $employee->hasKids) >0)
			<br>
			<h4>Djeca</h4>
			@foreach ($employee->hasKids as $kid)
				<p class="show_empl_p" >{{ $kid->first_name . ' ' . $kid->last_name . ' - ' . __('basic.b_day') . ': ' . date('d.m.Y',strtotime($kid->b_day)) }}</p>
			@endforeach
		@endif
	</div>
</main>