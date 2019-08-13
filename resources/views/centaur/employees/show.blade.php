@extends('Centaur::layout')

@section('title', 'Biografija')

@section('content')
<div class="index_page show_page">
	<header class="index_head">
		<a class="back" href="{{ url()->previous() }}">
			<i class="fas fa-angle-left"></i>
			@lang('basic.back')
		</a>

		<h1>@lang('home.profile')</h1>
	</header>
	<main class="show_main">
		<div class="employee f_left">
			<div class="info">
				<div class="profile_img" >
					@if($docs)
						<img class="radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($docs)) }}"  alt="Profile image"  />
					@else
						<img class="radius50" src="{{ URL::asset('img/profile.png') }}"  alt="Profile image"  />
					@endif
					<form action="{{ route('documents.store') }}" method="post" enctype="multipart/form-data" style="text-align:left;">
						<div class="inputWrapper">
							<input class="fileInput" type="file" name="fileToUpload" required /><i class="fas fa-camera" ></i>
						</div>
						<input name="profileIMG" value="profileIMG" type="hidden">
						<input name="employee_id" value="{{ $employee->id }}" type="hidden">
						{{ csrf_field() }}
						<input type="submit" value="Upload" name="submit">
					</form>
				</div>
				<h3>{{ $employee->user['last_name']  . ' '. $employee->user['first_name'] }}</h3>
				<p>{{ $employee->work['name'] }}</p>
				<p class="odjel">{{ $employee->work->department['name'] }}</p>
				<div class="contact">
					<p><span><i class="far fa-envelope"></i></span>{{ $employee->email}}</p>
					<p><span><i class="fas fa-phone"></i></span>{{ $employee->mobile}}</p>
					<p><span><i class="fas fa-phone"></i></span>{{ $employee->priv_mobile}}</p>
				</div>
				<footer class="w_100">
					<div class="">
						
						<span>N/A</p>
					</div>
					<div>
						
						<span>N/A</span>
					</div>
					<div class="bday">
						
						<span class="dan">{{  date("d", strtotime( $employee->b_day)) }} </span>
						<span>{{ date("F", strtotime( $employee->b_day)) }}</span>
						<span>{{ date("Y", strtotime( $employee->b_day)) }}</span>
					</div>
				</footer>
				
			</div>	
		</div>
		
	</main>
</div>	
@stop