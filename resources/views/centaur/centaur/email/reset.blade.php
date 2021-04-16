<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Reset Your Password</h2>

		<p>@lang('emailing.to_change_password'), <a href="{{ route('auth.password.reset.form', urlencode($code)) }}">@lang('emailing.click_here').</a></p>
		<p>@lang('emailing.or_point_browser') <br /> {!! route('auth.password.reset.form', urlencode($code)) !!} </p>
		<p>@lang('emailing.thank_you')</p>	
		
	</body>
</html>