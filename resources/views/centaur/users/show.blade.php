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
		@php		
			use App\Http\Controllers\DashboardController; 
			
			if($user->employee) {
				$image_employee = DashboardController::profile_image($user->employee->id);

				$user_name = DashboardController::user_name($user->employee->id);
				$work = $user->employee->work;
				
				if($work && $departmentRoles->where('department_id', $work->id)) {
					$dep_roles = explode(  ',', $departmentRoles->where('department_id', $work->id)->first()->permissions);
				}
			}
		@endphp
		<span class="user_img">
			@if($user->employee)
				<img class="radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($image_employee)) }}" alt="Profile image"  />
			@else
				<img class="radius50" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
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
			<span>3</span>
			<span>@lang('absence.vacation')<br>@lang('absence.days_used')</span>
		</span>
		<span class="days_left">
			<span>6</span>
			<span>@lang('absence.vacation')<br>@lang('absence.days_left')</span>
		</span>
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
				<label for="email">E-mail</label>
				<span id="email">{{ $user->employee->email }}</span>
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
$('a.edit_user[rel="modal:open"]').click(function(){
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

$('a[rel="modal:close"]').click(function(event) {
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

</script>