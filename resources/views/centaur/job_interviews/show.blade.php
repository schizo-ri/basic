<div class="modal-header">
	<h3 class="panel-title">@lang('basic.job_interview')</h3>
</div>
<div class="modal-body">
	<label for="">@lang('basic.fl_name')</label>
		<p>{{  $job_interview->first_name . ' ' .  $job_interview->last_name }}</p>
	<label for="">@lang('basic.date')</label>
		<p>{{ date('d.m.Y.', strtotime($job_interview->date)) }}</p>
	<label for="">OIB)</label>
		<p>{{ $job_interview->oib }}</p>
	<label for="">E-mail</label>
		<p>{{ $job_interview->email }}</p>
	<label for="">@lang('basic.phone')</label>
		<p>{{ $job_interview->phone }}</p>
	<label for="">@lang('basic.language')</label>
		<p>{{ $job_interview->language }}</p>
	<label for="">@lang('basic.title')</label>
		<p>{{ $job_interview->title }}</p>
	<label for="">@lang('basic.qualifications')</label>
		<p>{{ $job_interview->qualifications }}</p>
	<label for="">@lang('basic.work')</label>
		<p>{{ $job_interview->work->name }}</p>
	<label for="">@lang('basic.years_experience')</label>
		<p>{{ $job_interview->years_service }}</p>
	<label for="">@lang('basic.salary')</label>
		<p>{{ $job_interview->salary }}</p>
	<label for="">@lang('basic.comment')</label>
		<p>{{ $job_interview->comment }}</p>
</div>