@extends('Centaur::admin')

@section('title', __('basic.users'))

@section('content')
	@php
		use App\Http\Controllers\DashboardController; 
		use App\Http\Controllers\CompanyController; 
		$users_number = CompanyController::getUsersNumber();
		$users_number++; // + superadmin
	@endphp
	<header class="page-header user_header">
		<div class="index_table_filter">
			<label>
				<input type="search" placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
			</label>
			<span class="users_number" hidden >{{ $users_number }}</span>
			<span class="users_count" hidden >{{ count($users) }}</span>
			@if(Sentinel::getUser()->hasAccess(['users.create']))
				<a class="btn-new create_user" href="{{ route('users.create') }}" rel="modal:open">
					<i class="fas fa-plus"></i>
				</a>
			@endif	
			<span class="change_view"></span>
			<span class="change_view2"></span>
		</div>
	</header>
	<main class="col-xs-12 col-sm-12 col-md-12 col-lg-12 users_main">
		<div class="table-responsive">
			@if(count($users)>0)
				<table id="index_table" class="display table table-hover sort_1_asc">
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
							@if (! $user->inRole('superadmin'))
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
								<tr class="tr_open_link" data-href="/users/{{ $user->id }}" data-modal >
									<td>
										@if($user->employee &&  $image_employee != '' &&  ! empty($image_employee ) )
											<img class="radius50 float_l" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($image_employee)) }}" alt="Profile image"  />
										@else
											<img class="radius50 float_l" src="{{ URL::asset('img/profile.svg') }}" alt="Profile image"  />
										@endif	
										<span class="float_l">{{ $user->first_name . ' ' . $user->last_name }}<br>
										<span class="user_mail">{{ $user->email }}</span></span>
									</td>						
									<td>{!! isset($user->work_id) && $works->where('id', $user->work_id)->first() ? $works->where('id', $user->work_id)->first()->department['name'] . ' - ' .  $works->where('id', $user->work_id)->first()->name : '' !!}</td>
									<td>
										@if(isset($dep_roles))
											@for ($i = 0; $i < count($dep_roles); $i++)
												<span class="role _{{ $i }}">{{ $dep_roles[$i] }} </span>
											@endfor
											<span class="more"> + {{ count($dep_roles)-2 }} @lang('basic.more')</span>
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
										<!-- <button class="collapsible option_dots float_r"></button> -->
										@if(Sentinel::getUser()->hasAccess(['users.update']))
											<a href="{{ route('users.edit', $user->id) }}" class="" title="{{ __('basic.edit_user') }}" rel="modal:open">
													<i class="far fa-edit"></i>
											</a>
										@endif
										@if(Sentinel::getUser()->hasAccess(['employees.create']) && ! $employees->where('user_id',$user->id)->first())
											<a href="{{ route('employees.create', ['user_id' => $user->id] ) }}" class="" title="{{ __('basic.add_employee') }}" rel="modal:open">
												<i class="fas fa-user-plus"></i>
											</a>
										@endif
										@if(Sentinel::getUser()->hasAccess(['employees.update']) && $employees->where('user_id',$user->id)->first())
											<a href="{{ route('employees.edit',$employees->where('user_id',$user->id)->first()->id ) }}" class="" title="{{ __('basic.edit_employee') }}" rel="modal:open">
												<i class="fas fa-user-cog"></i>
											</a>
										@endif
										@if(Sentinel::getUser()->hasAccess(['users.delete'])&& ! $employees->where('user_id',$user->id)->first())
											<a href="{{ route('users.destroy', $user->id) }}" class="action_confirm danger" data-method="delete" data-token="{{ csrf_token() }}" >
												<i class="far fa-trash-alt"></i>
											</a>
										@endif
									</td>
								</tr>
							@endif
							
						@endforeach
					</tbody>
				</table>
			@endif
		</div>
		
		<div class="second_view">
			<div class="user_filter">
				<img class="img_search" src="{{ URL::asset('icons/filter.png')  }}" alt="Filter"/>
				<select id="filter1" class="select_filter sort" >
					<option class="sort_desc" value="ASC">
						@lang('basic.a_first')
					</option>
					<option class="sort_asc" value="DESC">
						@lang('basic.z_first')
					</option>
				<!--		<option class="sort_desc" value="date_ASC">
						@lang('basic.old_first')
					</option>
					<option class="sort_asc" value="date_DESC">
						@lang('basic.new_first')
					</option>-->
				</select>
			</div>
			<div class="user_cards" >
				@if(count($users))
					@foreach ($users as $user)
					@if (! $user->inRole('superadmin'))
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
						<div class="user_card" title="{{  $user->last_name }}">
							<div>
								<span>
									<!-- <button class="collapsible option_dots float_r"></button> -->
										@if(Sentinel::getUser()->hasAccess(['users.update']))
											<a href="{{ route('users.edit', $user->id) }}" class="edit_user" title="{{ __('basic.edit_user') }}" rel="modal:open">
												<i class="far fa-edit"></i>
											</a>
										@endif
										@if(Sentinel::getUser()->hasAccess(['employees.create']) && ! $employees->where('user_id',$user->id)->first())
											<a href="{{ route('employees.create', ['user_id' => $user->id] ) }}" class="edit_user" title="{{ __('basic.add_employee') }}" rel="modal:open">
												<i class="fas fa-user-plus"></i>
											</a>
										@endif
										@if(Sentinel::getUser()->hasAccess(['employees.update']) && $employees->where('user_id',$user->id)->first())
											<a href="{{ route('employees.edit',$employees->where('user_id',$user->id)->first()->id ) }}" class="edit_user" title="{{ __('basic.edit_employee') }}" rel="modal:open">
												<i class="fas fa-user-cog"></i>
											</a>
										@endif
										@if(Sentinel::getUser()->hasAccess(['users.delete'])&& !$employees->where('user_id',$user->id)->first())
											<a href="{{ route('users.destroy', $user->id) }}" class="action_confirm danger" data-method="delete" data-token="{{ csrf_token() }}" >
												<i class="far fa-trash-alt"></i>
											</a>
										@endif
								</span>	
								<a class="show_user" href="{{ route('users.show', $user->id) }}" rel="modal:open">				
									<header>
										<span class="user_img">
											@if(isset($image_employee) && $image_employee != ''  && ! empty($image_employee))
												<img class="radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($image_employee)) }}" alt="Profile image"  />
											@else
												<img class="radius50" src="{{ URL::asset('img/profile.svg') }}" alt="Profile image"  />
											@endif
										</span>
									</header>
									<main>
										<p class="user_name">{{ $user->first_name . ' ' . $user->last_name }}</p>
										<p class="user_mail">{{ $user->email }}</p>
										<p class="user_roles">
											@if ($user->roles->count() > 0)
												{{ $user->roles->implode('name', ', ') }}
											@else
												<em>No Assigned Role</em>
											@endif
										</p>
									</main>
								</a>
							</div>
						</div>	
						@endif					
					@endforeach
				@endif	
			</div>
		</div>
	</main>
	<div class="users_modal_error" >
		<span class="img-error"></span>
		<p>@lang('ctrl.max_user')</p> 
		<p>@lang('ctrl.call_duplico')</p> 
	</div>
	<div id="login-modal" class="modal modal_user modal_employee">
		
	</div>
	<script>
		$('.create_user').click(function(event){
			var users_number = $('.users_number').text();
			var users_count = $('.users_count').text();
			if(parseInt(users_count) >= parseInt(users_number)) {
				$('.users_modal_error').show();
				$('.users_modal_error').modal();

				return false;
			} else {
				return true;
			}
		});
		
		function Ascending_sort(a, b) { 
			return ($(b).attr('title').toUpperCase()) <  
				($(a).attr('title').toUpperCase()) ? 1 : -1;  
		} 
		function Descending_sort(a, b) { 
			return ($(b).attr('title').toUpperCase()) >  
				($(a).attr('title').toUpperCase()) ? 1 : -1;  
		} 
		$('.select_filter').on('change', function() {
			if($(this).val() == 'ASC') {
				$(".user_card").sort(Ascending_sort).appendTo('.user_cards'); 
			} else if($(this).val() == 'DESC') {
				$(".user_card").sort(Descending_sort).appendTo('.user_cards'); 
			}
		});	
	</script>
@stop