@php
	use App\Http\Controllers\DashboardController; 
@endphp
<header class="page-header">
	<div class="index_table_filter">
		<label>
			<input type="search" placeholder="{{ __('basic.search')}}" onkeyup="mySearchTable()" id="mySearchTbl">
		</label>
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
		@if(count($users))
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
					<tr>
						<td>
							@if($user->employee &&  $image_employee != '' &&  ! empty($image_employee ) )
								<img class="radius50 float_l" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($image_employee)) }}" alt="Profile image"  />
							@else
								<img class="radius50 float_l" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
							@endif	
							<span class="float_l"><a class="show_user" href="{{ route('users.show', ['user_id' => $user->id] ) }}" rel="modal:open" >{{ $user->first_name . ' ' . $user->last_name }}<i class="far fa-eye"></i></a><br>
							<span class="user_mail">{{ $user->email }}</span></span>
						</td>						
						<td>{!! isset($user->work_id) ? $works->where('id', $user->work_id)->first()->department['name'] . ' - ' .  $works->where('id', $user->work_id)->first()->name : '' !!}</td>
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
							<button class="collapsible option_dots float_r"></button>
							@if(Sentinel::getUser()->hasAccess(['users.update']))
								<a href="{{ route('users.edit', $user->id) }}" class="" title="{{ __('basic.edit_user') }}" style="display:none" rel="modal:open">
										<i class="far fa-edit"></i>
								</a>
							@endif
							@if(Sentinel::getUser()->hasAccess(['employees.create']) && ! $employees->where('user_id',$user->id)->first())
								<a href="{{ route('employees.create', ['user_id' => $user->id] ) }}" class="" title="{{ __('basic.add_employee') }}" style="display:none" rel="modal:open">
									<i class="fas fa-user-plus"></i>
								</a>
							@endif
							@if(Sentinel::getUser()->hasAccess(['employees.update']) && $employees->where('user_id',$user->id)->first())
								<a href="{{ route('employees.edit',$employees->where('user_id',$user->id)->first()->id ) }}" class="" title="{{ __('basic.edit_employee') }}" style="display:none" rel="modal:open">
									<i class="fas fa-user-cog"></i>
								</a>
							@endif
							@if(Sentinel::getUser()->hasAccess(['users.delete'])&& ! $employees->where('user_id',$user->id)->first())
								<a href="{{ route('users.destroy', $user->id) }}" class="action_confirm danger" data-method="delete" data-token="{{ csrf_token() }}" style="display:none" >
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
								<button class="collapsible option_dots float_r"></button>
									@if(Sentinel::getUser()->hasAccess(['users.update']))
										<a href="{{ route('users.edit', $user->id) }}" class="edit_user" title="{{ __('basic.edit_user') }}" style="display:none" rel="modal:open">
											<i class="far fa-edit"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['employees.create']) && ! $employees->where('user_id',$user->id)->first())
										<a href="{{ route('employees.create', ['user_id' => $user->id] ) }}" class="edit_user" title="{{ __('basic.add_employee') }}" style="display:none" rel="modal:open">
											<i class="fas fa-user-plus"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['employees.update']) && $employees->where('user_id',$user->id)->first())
										<a href="{{ route('employees.edit',$employees->where('user_id',$user->id)->first()->id ) }}" class="edit_user" title="{{ __('basic.edit_employee') }}" style="display:none" rel="modal:open">
											<i class="fas fa-user-cog"></i>
										</a>
									@endif
									@if(Sentinel::getUser()->hasAccess(['users.delete'])&& !$employees->where('user_id',$user->id)->first())
										<a href="{{ route('users.destroy', $user->id) }}" class="action_confirm danger" data-method="delete" data-token="{{ csrf_token() }}" style="display:none" >
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
											<img class="radius50" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
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
				@endforeach
			@endif	
		</div>
	</div>
</main>
<script>
	jQuery(document).ready(function($) {
		$("a.show_user").click(function(event) {
			$.modal.defaults = {
				closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
				escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
				clickClose: false,       // Allows the user to close the modal by clicking the overlay
				closeText: 'Close',     // Text content for the close <a> tag.
				closeClass: '',         // Add additional class(es) to the close <a> tag.
				showClose: true,        // Shows a (X) icon/link in the top-right corner
				modalClass: "modal modal_user",    // CSS class added to the element being displayed in the modal.
				// HTML appended to the default spinner during AJAX requests.
				spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

				showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
				fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
				fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
			};
		});
		$("a.edit_user").click(function(event) {
			$.modal.defaults = {
				closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
				escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
				clickClose: false,       // Allows the user to close the modal by clicking the overlay
				closeText: 'Close',     // Text content for the close <a> tag.
				closeClass: '',         // Add additional class(es) to the close <a> tag.
				showClose: true,        // Shows a (X) icon/link in the top-right corner
				modalClass: "modal modal_user",    // CSS class added to the element being displayed in the modal.
				// HTML appended to the default spinner during AJAX requests.
				spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

				showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
				fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
				fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
			};
		});
		$("a.create_user").click(function(event) {
			$.modal.defaults = {
				closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
				escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
				clickClose: false,       // Allows the user to close the modal by clicking the overlay
				closeText: 'Close',     // Text content for the close <a> tag.
				closeClass: '',         // Add additional class(es) to the close <a> tag.
				showClose: true,        // Shows a (X) icon/link in the top-right corner
				modalClass: "modal",    // CSS class added to the element being displayed in the modal.
				// HTML appended to the default spinner during AJAX requests.
				spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

				showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
				fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
				fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
			};
		});
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
	$(function(){
		$.getScript( '/../js/users.js');   
		$.getScript( '/../js/collaps.js'); 
	});

	$.getScript( '/../restfulizer.js');
</script>