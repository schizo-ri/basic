<!DOCTYPE html>
<html>
	<head>
		<title>@lang('basic.employees')</title>
		<script>var dt = new Date().getTime();</script>
		<!-- CSS -->
		<link rel="stylesheet" href="{{ URL::asset('/../css/all.css?random=@dt') }}"/>
	</head>
	<body>
		<section class="modal modal_user modal_employee" >
			<header >
				<h4 class="panel-title">@lang('basic.employee') {{  $employee->user->first_name . ' ' .  $employee->user->last_name }}</h4>
			</header>
			<main class="">
				<div class="basic_info">
					<h4>Osobni podaci</h4>
					<div><label class="show_empl_label" for="">@lang('basic.fl_name')</label>
						<p class="show_empl_p" >{{  $employee->user->first_name . ' ' .  $employee->user->last_name }}</p></div>
					<div><label class="show_empl_label" for="">@lang('basic.b_day')</label>
						<p class="show_empl_p" >{{ date('d.m.Y',strtotime($employee->b_day)) }}</p></div>
					<div><label class="show_empl_label" for="">@lang('basic.b_place')</label>
						<p class="show_empl_p" >{{  $employee->b_place  }}</p></div>
					<div><label class="show_empl_label" for="">@lang('basic.father_name')</label>
						<p class="show_empl_p" >{{  $employee->father_name  }}</p></div>
					<div><label class="show_empl_label" for="">@lang('basic.mather_name')</label>
						<p class="show_empl_p" >{{  $employee->mather_name  }}</p></div>
					<div><label class="show_empl_label" for="">@lang('basic.maiden_name')</label>
						<p class="show_empl_p" >{{  $employee->maiden_name  }}</p></div>
					<div><label class="show_empl_label" for="">OIB</label>
						<p class="show_empl_p" >{{ $employee->oib }}</p></div>
					<div><label class="show_empl_label" for="">@lang('basic.oi')</label>
						<p class="show_empl_p" >{{ $employee->oi }}</p></div>
					<div><label class="show_empl_label" for="">@lang('basic.oi_expiry')</label>
						<p class="show_empl_p" >{{ date('d.m.Y',strtotime($employee->oi_expiry)) }}</p></div>
					<div><label class="show_empl_label" for="">@lang('basic.marital')</label>
						<p class="show_empl_p" >{!! $employee->marital == 'yes' ? 'Oženjen / udata' : '' !!}</p></div>
					</div>
				</div>
				<div class="basic_info">
					<h4>Kontakt</h4>
					<div><label class="show_empl_label" for="">@lang('basic.priv_email')</label>
						<p class="show_empl_p" >{{ $employee->priv_email }}</p></div>
					<div><label class="show_empl_label" for="">E-mail</label>
						<p class="show_empl_p" >{{ $employee->email }}</p></div>
					<div><label class="show_empl_label" for="">@lang('basic.priv_mobile')</label>
						<p class="show_empl_p" >{{ $employee->priv_mobile }}</p></div>
					<div><label class="show_empl_label" for="">@lang('basic.mobile')</label>
						<p class="show_empl_p" >{{ $employee->mobile }}</p></div>
					
					<div><label class="show_empl_label" for="">Prebivalište adresa</label>
						<p class="show_empl_p" >{{ $employee->prebiv_adresa }}</p></div>
					<div><label class="show_empl_label" for="">Prebivalište grad</label>
						<p class="show_empl_p" >{{ $employee->prebiv_grad }}</p></div>
					<div><label class="show_empl_label" for="">Boravište adresa</label>
						<p class="show_empl_p" >{{ $employee->borav_adresa }}</p></div>
					<div><label class="show_empl_label" for="">Boravište grad</label>
						<p class="show_empl_p" >{{ $employee->borav_grad }}</p></div>
					</div>
				</div>
				<div class="basic_info">
					<h4>Podaci o zaposlenju</h4>
					<div><label class="show_empl_label" for="">@lang('basic.reg_date')</label>
						<p class="show_empl_p" >{{ date('d.m.Y',strtotime($employee->reg_date)) }}</p></div>
					<div><label class="show_empl_label" for="">@lang('basic.work')</label>
						<p class="show_empl_p" >{{ $employee->work->name }}</p></div>
					<div><label class="show_empl_label" for="">@lang('basic.metier')</label>
						<p class="show_empl_p" >{{ $employee->title }}</p></div>
					<div><label class="show_empl_label" for="">@lang('basic.qualifications')</label>
						<p class="show_empl_p" >{{ $employee->qualifications }}</p></div>
					
					<div><label class="show_empl_label" for="">Staž kod prošlog poslodavca (godina-mjeseci-dana):</label>
						<p class="show_empl_p" >{{ $employee->years_service }}</p></div>

					<div><label class="show_empl_label" for="">@lang('basic.probation')</label>
						<p class="show_empl_p" >{{ $employee->probation }}</p></div>
					<div><label class="show_empl_label" for="">Prekid radnog odnosa više od 8 dana</label>
						<p class="show_empl_p" >{!! $employee->termination_service == 1 ? 'DA' : 'NE' !!}</p></div>
					<div><label class="show_empl_label" for="">Prvo zaposlenje</label>
						<p class="show_empl_p" >{!! $employee->first_job == 1 ? 'DA' : 'NE' !!}</p></div>
					@if(Sentinel::inRole('view_efc') || Sentinel::inRole('uprava') )
						<div><label class="show_empl_label" for="">Efektivna cijena sata:</label>
							<p class="show_empl_p" >{{ $employee->effective_cost }} Kn</p></div>
						<div><label class="show_empl_label" for="">Brutto godišnja plaća:</label>
							<p class="show_empl_p" >{{ $employee->brutto }} Kn</p></div>
					@endif
					<div><label class="show_empl_label" for="">@lang('basic.lijecn_pregled')</label>
						<p class="show_empl_p" >{{ date('d.m.Y',strtotime($employee->lijecn_pregled)) }}</p></div>
					<div><label class="show_empl_label" for="">@lang('basic.znr')</label>
						<p class="show_empl_p" >{{ date('d.m.Y',strtotime($employee->znr)) }}</p></div>
					</div>
				</div>
				<div class="basic_info">
					<h4>Ostalo</h4>
					<div><label class="show_empl_label" for="">@lang('basic.size')</label>
						<p class="show_empl_p" >{{ $employee->size }}</p></div>
					<div><label class="show_empl_label" for="">@lang('basic.shoe_size')</label>
						<p class="show_empl_p" >{{ $employee->shoe_size }}</p></div>
					<div><label class="show_empl_label" for="">Obračun prekovremenih kao:</label>
						<p class="show_empl_p" >{!! $employee->days_off == 1 ? 'Slobodni dani' : 'Isplata' !!}</p></div>
					<div><label class="show_empl_label" for="">Djelatnik je stranac</label>
						<p class="show_empl_p" >{!! $employee->stranger == 1 ? 'DA' : 'NE' !!}</p></div>
					<div><label class="show_empl_label" for="">Datum isteka dozvole boravka u RH:</label>
						<p class="show_empl_p" >{!! $employee->permission_date ? date('d.m.Y',strtotime($employee->permission_date)) : '' !!}<p>
					</div>
					<div><label class="show_empl_label" for="">@lang('basic.comment')</label>
						<p class="show_empl_p" >{{ $employee->comment }}</p></div>
				</div>
				@if (count( $employee->hasKids) >0)
					<div class="basic_info">
						<h4>Djeca</h4>
						@foreach ($employee->hasKids as $kid)
						<div><p class="show_empl_p" style="padding-left:0">{{ $kid->first_name . ' ' . $kid->last_name . ' - ' . __('basic.b_day') . ': ' . date('d.m.Y',strtotime($kid->b_day)) }}</p></div>
						@endforeach
					</div>
				@endif
			</main>
		</section>
	</body>
</html>

