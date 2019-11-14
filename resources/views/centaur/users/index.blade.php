@php
	use App\Http\Controllers\DashboardController; 
@endphp
<header class="page-header">
	<div class="index_table_filter">
		<label>
			<input type="search"  placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
		</label>
		@if(Sentinel::getUser()->hasAccess(['users.create']))
			<a class="btn-new" href="{{ route('users.create') }}" rel="modal:open">
				<i class="fas fa-plus"></i>
			</a>
		@endif
		<span class="change_view"></span>
	</div>
</header>
<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="table-responsive">
		@if(count($users))
			<table id="index_table" class="display table table-hover">
				<thead>
					<tr>
						<th>@lang('basic.fl_name')</th>
						<th>@lang('basic.work')</th>
						<th>@lang('basic.dep_permissions')</th>
						<th>@lang('basic.permissions')</th>
						<th class="not-export-column">@lang('basic.options')</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($users as $user)
					@php
						$image_employee = '';
						if($user->employee) {
							$image_employee = DashboardController::profile_image($user->employee->id);
							$user_name = DashboardController::user_name($user->employee->id);
						}
						
						if(isset($user->work_id) && $departmentRoles->where('department_id', $user->work_id)->first()) {
							$dep_roles = explode(  ',', $departmentRoles->where('department_id', $user->work_id)->first()->permissions);
						}
					@endphp
						<tr>
							<td>
								@if(isset($image_employee) && $image_employee != '')
									<img class="radius50 float_l" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($image_employee)) }}" alt="Profile image"  />
								@else
									<img class="radius50 float_l" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
								@endif	
								<span class="float_l">{{ $user->first_name . ' ' . $user->last_name }}<br>
								
								<span class="user_mail">{{ $user->email }}</span></span>
							</td>
						
							<td>{!! isset($user->work_id) ? $works->where('id', $user->work_id)->first()->department['name'] . ' - ' .  $works->where('id', $user->work_id)->first()->name : '' !!}</td>
							<td>
								@if(isset($dep_roles))
									@for ($i = 0; $i < count($dep_roles); $i++)
										<span class="role _{{ $i }}">{{ $dep_roles[$i] }}</span>
									@endfor
									<span class="more">+ {{ count($dep_roles)-2 }} @lang('basic.more')</span>
									<span class="hide">@lang('basic.hide')</span>
								@endif
							</td>
							<td>
								@if ($user->roles->count() > 0)
									{{ $user->roles->implode('name', ', ') }}
								@else
									<em>No Assigned Role</em>
								@endif
							</td>
							<td class="center">
								<button class="collapsible option_dots float_r"></button>
								@if(Sentinel::getUser()->hasAccess(['users.update']))
									<a href="{{ route('users.edit', $user->id) }}" class="" title="{{ __('basic.edit_user') }}"  rel="modal:open">
											<i class="far fa-edit"></i>
									</a>
								@endif
								@if(Sentinel::getUser()->hasAccess(['employees.create']) && ! $employees->where('user_id',$user->id)->first())
										<a href="{{ route('employees.create', ['user_id' => $user->id] ) }}" class="" title="{{ __('basic.add_employee') }}"  rel="modal:open">
										<i class="fas fa-user-plus"></i>
									</a>
								@endif
								@if(Sentinel::getUser()->hasAccess(['employees.update']) && $employees->where('user_id',$user->id)->first())
										<a href="{{ route('employees.edit',$employees->where('user_id',$user->id)->first()->id ) }}" class="" title="{{ __('basic.edit_employee') }}"  rel="modal:open">
										<i class="fas fa-user-cog"></i>
									</a>
								@endif
								@if(Sentinel::getUser()->hasAccess(['users.delete'])&& !$employees->where('user_id',$user->id)->first())
									<a href="{{ route('users.destroy', $user->id) }}" class="action_confirm danger" data-method="delete" data-token="{{ csrf_token() }}">
										<i class="far fa-trash-alt"></i>
									</a>
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@endif
	</div>
</main>
<script>
	$(function(){
		$.getScript( '/../js/filter_table.js');
		$.getScript( '/../js/collaps.js');
		
		$('.more').click(function(){
			$('.role').toggle();
			$('.more').hide();
			$('.hide').show();
		});
		$('.hide').click(function(){
			$('.role').hide();
			$('.role._0').show();
			$('.role._1').show();

			$('.more').show();
			$('.hide').hide();
		});
		
	});
</script>