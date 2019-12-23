<header>
	<a class="back" href="{{ url()->previous() }}">
		<i class="fas fa-angle-left"></i>
	</a>
	<div>
			dd($user);
		@if ($user->employee)
			@php
				use App\Http\Controllers\DashboardController; 
				$image_employee = DashboardController::profile_image($user->employee->id);
				$user_name = DashboardController::user_name($user->employee->id);
				
				if(isset($user->work_id) && $departmentRoles->where('department_id', $user->work_id)->first()) {
					$dep_roles = explode(  ',', $departmentRoles->where('department_id', $user->work_id)->first()->permissions);
				}
			@endphp
		@endif
		<span class="user_img">
			@if(isset($image_employee) && $image_employee != '')
				<img class="radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($image_employee)) }}" alt="Profile image"  />
			@else
				<img class="radius50" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
			@endif
		</span>		
		<span>
			<span>{{ $user->first_name . ' ' .  $user->last_name }}</span>
			<span>{!! $user->employee ? $user->employee->work['name'] : '' !!}</span>
		</span>
	</div>

	<h1>korisnik</h1>
</header>
<main>

</main>