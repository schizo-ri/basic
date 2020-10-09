@php
	use App\Http\Controllers\BasicAbsenceController; 
@endphp
<header>
	<a class="back" rel="modal:close">
		<span class="arrow_left_wh" />
	</a>
	@if(Sentinel::getUser()->hasAccess(['users.update']))
		<a href="{{ route('users.edit', $user->id) }}" class="edit_user" title="{{ __('basic.edit_user') }}" rel="modal:open">
			<i class="far fa-edit"></i>edit
		</a>
	@endif
	<div class="user_data">
		<div class="col-3 info_abs user_img">
			<span class="user_img">
				@if($user->employee && is_array($image_employee))
					<img class="radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($image_employee)) }}" alt="Profile image"  />
				@else
					<img class="radius50" src="{{ URL::asset('img/profile.svg') }}" alt="Profile image"  />
				@endif
			</span>
			<span class="user_name">{{ $user->first_name . ' ' . $user->last_name  }}
				@if ($user->employee)
					@if ($user->employee->work) 
						<span class="work">{!! $work ? $work->name : '' !!}</span>
					@endif
				@endif
			</span>	
			
			{{-- <span class="user_name">{{ $user->first_name . ' ' .  $user->last_name}}</span> --}}
			{{-- <span class="work">{{ $user->employee->work['name'] }}</span> --}}
		</div>
		<div class="col-3 info_abs">
			@if($user->employee)
				<span class="title">@lang('absence.work_history')</span>
				<p class="col-6 float_l">
					{!! $data_absence ? $data_absence['years_service']->y . '-' . 
					$data_absence['years_service']->m . '-' .  $data_absence['years_service']->d : 0 !!}
					<span>@lang('absence.experience')<br><small>@lang('absence.yy_mm_dd')</small></span>
				<p class="col-6 float_l">
					{!! $data_absence ? $data_absence['all_servise'][0] . '-' . $data_absence['all_servise'][1]  . '-' .  $data_absence['all_servise'][2]  : 0 !!}
					<span>@lang('absence.experience') @lang('absence.total')<br><small>@lang('absence.yy_mm_dd')</small></span>
				</p>
			@endif
		</div>
		<div class="col-3 info_abs">
			@if($user->employee)
				<span class="title">@lang('absence.vacat_days')
					<select id="year_vacation" class="year_select">
						@foreach ($requests[ 'years'] as $year)
							<option >{{ $year }}</option>
						@endforeach
					</select>
				</span>
				<p class="col-6 float_l">
					@foreach ($requests[ 'years'] as $year)
						<span class="go go_{{ $year }}">{!! BasicAbsenceController::godisnjiGodina($user->employee, $year) ? BasicAbsenceController::godisnjiGodina($user->employee, $year) : '0'!!} ( {!! BasicAbsenceController::razmjeranGO_Godina($user->employee, $year) ? BasicAbsenceController::razmjeranGO_Godina($user->employee, $year) : '0' !!} )
						</span>
					@endforeach	
					<span>@lang('absence.total_days') <br> ( @lang('absence.proportion') ) </span>
				</p>
				<p class="col-6 float_l">
					@foreach ($requests[ 'years'] as $year)
						<span class="go go_{{ $year }}">
							{!! isset($data_absence['zahtjevi'][ $year]) ? count($data_absence['zahtjevi'][ $year]) : 0 !!}
							- {{ BasicAbsenceController::razmjeranGO_Godina($user->employee, $year) - count($data_absence['zahtjevi'][ $year])}}
							
						</span>
					@endforeach
					<span>@lang('absence.used_unused')</span>
				</p>
			@endif
		</div>
		<div class="col-3 info_abs">
			@if($user->employee)
				<span class="title">@lang('absence.sick_leave')
					<select id="year_sick" class="year_select">
						@foreach ($years as $year)
							<option>{{ $year }}</option>
						@endforeach
					</select>
				</span>
				<p class="col-6 float_l">
					@if(count($requests[ 'years'] ) > 0)
						@foreach ($years as $year)
							<span class="bol bol_{{ $year }}">{!! isset($bolovanje[ $year]) ? $bolovanje[ $year] : 0 !!}</span>
						@endforeach
					@endif
					<span>@lang('absence.total_used')</span>
				</p>
				<p class="col-6 float_l">
					<span class="bol_om">{!! isset($bolovanje['bolovanje_OM']) ? $bolovanje['bolovanje_OM'] : 0 !!}</span>
					<span>@lang('absence.this_month')</span>
				</p>
			@endif
		</div>
		{{-- <span class="user_img">
			@if($user->employee && is_array($image_employee))
				<img class="radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($image_employee)) }}" alt="Profile image"  />
			@else
				<img class="radius50" src="{{ URL::asset('img/profile.svg') }}" alt="Profile image"  />
			@endif
		</span>
		<span class="user_name">
			<span class="name">{{ $user->first_name . ' ' . $user->last_name  }}
				@if ($user->employee)
					@if ($user->employee->work) 
						<span class="work">{!! $work ? $work->name : '' !!}</span>
					@endif
				@endif
			</span>	
		</span>
		<span class="days_used">
			<span>{{ $requests[ date('Y') ]['dani_zahtjeva'] }}</span>
			<span>@lang('absence.vacation')<br>@lang('absence.days_used') {{ date('Y')}}</span>
		</span>
		<span class="days_left">
			<span>{{ $requests[ 'ukupnoPreostalo' ]}}</span>
			<span>@lang('absence.vacation')<br>@lang('absence.days_left')</span>
		</span> --}}
	</div>
</header>
<main>
	<div class="basic_info">
		<h4>@lang('basic.basic_info')
			@if(Sentinel::getUser()->hasAccess(['employees.update']) && $user->employee)
				<a href="{{ route('employees.edit',$user->employee->id ) }}" class="edit_user" title="{{ __('basic.edit_employee') }}" rel="modal:open">
					<i class="far fa-edit"></i>edit
				</a>
			@endif
		</h4>
		@if ($user->employee)			
			<div>
				<label for="bday">@lang('basic.birthday')</label>
				<span id="bday">{{ date('d.m.Y',strtotime($user->employee->b_day)) }}</span>
				<label for="work_place">@lang('basic.work')</label>
				<span id="work_place">{{ $user->employee->work->name }}</span>
				<label for="reg_date">@lang('basic.reg_date')</label>
				<span id="reg_date">{{ date('d.m.Y',strtotime($user->employee->reg_date))}} </span>
				<label for="phone">@lang('basic.phone')</label>
				<span id="phone">{{ $user->employee->mobile }}</span>
				<label for="email_info">E-mail</label>
				<span id="email_info">{{ $user->employee->email }}</span>
			</div>			
		@else
			@if(Sentinel::getUser()->hasAccess(['employees.create']))
				<a href="{{ route('employees.create', ['user_id' => $user->id] ) }}" class="edit_user" title="{{ __('basic.add_employee') }}" rel="modal:open">
					<i class="far fa-edit"></i>edit
				</a>
			@endif
		@endif
	</div>
	<div class="permissions">
		<h4>@lang('basic.permissions')
			<!--
			@if(Sentinel::getUser()->hasAccess(['roles.update']))
				<a href="{{ route('roles.edit',$user->id ) }}" class="edit_user" title="{{ __('basic.edit_role') }}" rel="modal:open">
					<i class="far fa-edit"></i>edit
				</a>
			@endif
			-->
		</h4>		
		<div>
			@if ($user->roles->count() > 0)
				@foreach ($user->roles as $role)
					<span class="role">{{ $role->name }}</span>
				@endforeach
			@else
				<em>No Assigned Role</em>
			@endif
		</div>

		
	</div>
</main>
<script>
	/* $.getScript( '/../js/open_modal.js');  */
	$('#year_vacation').change(function(){
		year = $(this).val();
		
		$('.info_abs>p>.go').hide();
		$('.info_abs>p>.go.go_'+year).show();
		$('#mySearchTbl').val("");


	});
	$('#year_sick').change(function(){
		console.log($(this).val());
		year = $(this).val();
		$('.info_abs>p>.bol').hide();
		$('.info_abs>p>.bol.bol_'+year).show();

		}); 
</script>