<header >
	<h4 class="panel-title">@lang('basic.employee')</h4>
</header>
<main class="">
	<div class="basic_info">
		<h4>Osobni podaci</h4>
		<label for="">@lang('basic.fl_name')</label>
			<p>{{  $employee->user->first_name . ' ' .  $employee->user->last_name }}</p>
		<label for="">@lang('basic.b_day')</label>
			<p>{{ date('d.m.Y',strtotime($employee->b_day)) }}</p>
		<label for="">@lang('basic.b_place')</label>
			<p>{{  $employee->b_place  }}</p>
		<label for="">@lang('basic.father_name')</label>
			<p>{{  $employee->father_name  }}</p>
		<label for="">@lang('basic.mather_name')</label>
			<p>{{  $employee->mather_name  }}</p>
		<label for="">@lang('basic.maiden_name')</label>
			<p>{{  $employee->maiden_name  }}</p>
		<label for="">OIB</label>
			<p>{{ $employee->oib }}</p>
		<label for="">@lang('basic.oi')</label>
			<p>{{ $employee->oi }}</p>
		<label for="">@lang('basic.oi_expiry')</label>
			<p>{{ date('d.m.Y',strtotime($employee->oi_expiry)) }}</p>
		<label for="">@lang('basic.marital')</label>
			<p>{{ $employee->marital }}</p>
		<br>
	</div>
	<div class="basic_info">
		<h4>Kontakt</h4>

		<label for="">@lang('basic.priv_email')</label>
		<p>{{ $employee->priv_email }}</p>
		<label for="">E-mail</label>
			<p>{{ $employee->email }}</p>
		<label for="">@lang('basic.priv_mobile')</label>
			<p>{{ $employee->priv_mobile }}</p>
		<label for="">@lang('basic.mobile')</label>
			<p>{{ $employee->mobile }}</p>
		<br>
		<label for="">@lang('basic.prebiv_adresa')</label>
			<p>{{ $employee->prebiv_adresa }}</p>
		<label for="">@lang('basic.prebiv_grad')</label>
			<p>{{ $employee->prebiv_grad }}</p>
		<label for="">@lang('basic.borav_adresa')</label>
			<p>{{ $employee->borav_adresa }}</p>
		<label for="">@lang('basic.borav_grad')</label>
			<p>{{ $employee->borav_grad }}</p>
		<br>
	</div>
	<div class="basic_info">
		<h4>Podaci o zaposlenju</h4>
		<label for="">@lang('basic.reg_date')</label>
			<span>{{ date('d.m.Y',strtotime($employee->reg_date)) }}</span>
		<label for="">@lang('basic.work')</label>
			<span>{{ $employee->work->name }}</span>
		<label for="">@lang('basic.superior')</label>
			<span>{{ $employee->work->name }}</span>
		<label for="">@lang('basic.title')</label>
			<span>{{ $employee->title }}</span>
		<label for="">@lang('basic.qualifications')</label>
			<span>{{ $employee->qualifications }}</span>
		
		<label for="">@lang('basic.years_service')</label>
			<span>{{ $employee->years_service }}</span>
		<label for="">@lang('basic.salary')</label>
			<span>{{ $employee->salary }}</span>

		<label for="">@lang('basic.probation')</label>
			<span>{{ $employee->probation }}</span>
		<label for="">@lang('basic.termination_service')</label>
			<span>{{ $employee->termination_service }}</span>
		<label for="">@lang('basic.first_job')</label>
			<span>{{ $employee->first_job }}</span>
		<label for="">@lang('basic.effective_cost')</label>
			<span>{{ $employee->effective_cost }}</span>
		<label for="">@lang('basic.brutto')</label>
			<span>{{ $employee->brutto }}</span>
		<label for="">@lang('basic.comment')</label>
			<span>{{ $employee->comment }}</span>
		<label for="">@lang('basic.lijecn_pregled')</label>
			<span>{{ date('d.m.Y',strtotime($employee->lijecn_pregled)) }}</span>
		<label for="">@lang('basic.znr')</label>
			<span>{{ date('d.m.Y',strtotime($employee->znr)) }}</span>
		<br>
	</div>
	<div class="basic_info">
		<label for="">@lang('basic.size')</label>
			<span>{{ $employee->size }}</span>
		<label for="">@lang('basic.shoe_size')</label>
			<span>{{ $employee->shoe_size }}</span>
		<label for="">@lang('basic.days_off')</label>
			<span>{{ $employee->days_off }}</span>
		<label for="">@lang('basic.stranger')</label>
			<span>{{ $employee->stranger }}</span>
		<label for="">@lang('basic.permission_date')</label>
			<span>{{ date('d.m.Y',strtotime($employee->permission_date)) }}</span>
		<br>
		<label for="">@lang('basic.comment')</label>
			<span>{{ $employee->comment }}</span>
	</div>
</main>