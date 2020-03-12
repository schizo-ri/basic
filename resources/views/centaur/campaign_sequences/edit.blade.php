<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
		<meta charset="UTF-8">
		<meta name="description" content="Portal za zaposlenike">
		<meta name="author" content="Jelena Juras">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>@yield('title')</title>
        <!-- Bootstrap - Latest compiled and minified CSS -->
		<link rel="stylesheet" href="{{ URL::asset('/../node_modules/bootstrap/dist/css/bootstrap.min.css') }}"/>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
		
		<!--Awesome icons -->
		<link rel="stylesheet" href="{{ URL::asset('/../node_modules/@fortawesome/fontawesome-free/css/all.min.css') }}"/>

		<!-- JS modal -->
		<link rel="stylesheet" href="{{ URL::asset('/../node_modules/jquery-modal/jquery.modal.min.css') }}" type="text/css" />

		<!-- CSS -->
		<link rel="stylesheet" href="{{ URL::asset('/../css/campaign.css') }}"/>
		<!-- ICON -->
		<link rel="shortcut icon" href="{{ asset('img/icon.ico') }}">
		<script src="//editor.unlayer.com/embed.js"></script>
		<!--Jquery -->
		<script src="{{ URL::asset('/../node_modules/jquery/dist/jquery.min.js') }}"></script>
		@stack('stylesheet')
	</head>
	<body>
		<form class="form_sequence" accept-charset="UTF-8" role="form" method="post" action="{{ route('campaign_sequences.update', $campaign_sequence->id ) }}">
			<input type="hidden" name="campaign_id" id="campaign_id" value="{{  $campaign_sequence->campaign_id }}">
			<input type="hidden" name="id" id="id" value="{{  $campaign_sequence->id }}">
			<textarea name="text_html" id="text_html" hidden >{{ $campaign_sequence->text }}</textarea>
			<textarea name="text_json" id="text_json" hidden >{{ $campaign_sequence->text_json }}</textarea>
			<header>
				{{ csrf_field() }}
				{{ method_field('PUT') }}
				<div class="unlayer container">
					<button  class="btn-submit" {{-- (click)="exportHtml()" --}}>@lang('basic.save')</button>
					<email-editor></email-editor>
					{{-- 	<input class="btn-submit" type="submit" value="{{ __('basic.save')}}"> --}}
					<a class="btn-back" href="{{ url()->previous() }}">
						@lang('basic.back')
					</a>
				</div>			
				<h3 class="panel-title">@lang('basic.add_sequence')  {{ $campaign_sequence->campaign['name']  }}{{--  {!! $this_campaign ? count($campaign_sequences)+1 : '' !!} --}} </h3>
			</header>
			<main>
				<div id="editor-container"></div>
			</main>
		</form>

		<span hidden class="locale" >{{ App::getLocale() }}</span>
		<span hidden class="dataArr">{{ ($campaign_sequence->text_json) }}</span>

		<!-- Latest compiled and minified Bootstrap JavaScript -->
        <!-- Bootstrap js -->
		<script src="{{ URL::asset('/../node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
		<script src="{{ URL::asset('/../node_modules/popper.js/dist/umd/popper.min.js') }}"></script>
        <!-- Restfulizer.js - A tool for simulating put,patch and delete requests -->
        <script src="{{ asset('/../restfulizer.js') }}"></script>
				
		<script>
			var design = JSON.parse( $('.dataArr').text()); // template JSON */
			var form_data = $('.form_sequence').serialize();
			var url = $('form.form_sequence').attr('action');
			
			var data_new = {};
			var json = '';
			var html = '';
			var id = $('#id').val();
			
			unlayer.init({
				id: 'editor-container',
				projectId: form_data['campaign_id'],
				displayMode: 'email'
			})

			unlayer.loadDesign(design);

			unlayer.addEventListener('design:updated', function(updates) {
				unlayer.exportHtml(function(data) {
					json = data.design; // design json
					html = data.html; // design html

					$('#text_html').text( html.replace(/\n\s+|\n/g, ""));
					$('#text_json').text(JSON.stringify(json));

				})
			})		

			$('.btn-submit').click(function(e) {
				e.preventDefault();
				form_data = $('.form_sequence').serialize();
				data_new = form_data + '&start_date=' + '2020-03-15';

				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

				$.ajax({
					url: url,
					type: "post",
					data: data_new,
					success: function( response ) {
						alert("Dizajn je spremljen!");
					}, 
					error: function(xhr,textStatus,thrownError) {
						console.log("validate eror " + xhr + "\n" + textStatus + "\n" + thrownError); 							         
					}
				});
			});
		</script>

		<!--Awesome icons -->
		<script src="{{ URL::asset('/../node_modules/@fortawesome/fontawesome-free/js/all.min.js') }}"></script>
	
		<!-- Jquery modal -->
		<script src="{{ URL::asset('/../node_modules/jquery-modal/jquery.modal.min.js') }}"></script>
		
		<!--Unlayer modal -->
		{{-- <script src="{{ URL::asset('/../node_modules/react-email-editor/umd/react-email-editor.min.js') }}"></script> --}}

		<!-- Scripts -->
		<script src="{{URL::asset('/../js/open_modal.js') }}"></script>
		<script src="{{URL::asset('/../js/campaign_sequences.js') }}"></script>

		<!-- tinymce js -->
		<script src="{{ URL::asset('/node_modules/tinymce/tinymce.min.js') }}" ></script>
		
		@if(session()->has('modal'))
			<script>
				$('.row.notification').modal();
			</script>
		@endif
		@stack('script')		
    </body>
</html>