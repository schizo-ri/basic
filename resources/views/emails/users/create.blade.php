<!DOCTYPE html>
<html lang="hr">
	<head>
        <meta charset="utf-8">
		@include('Centaur::mail_style')
	</head>
	<body>
        <div style="width: 500px;max-width:100%;margin:auto;" id="mail_template">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="header" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_header : '' !!}">
                @if(count($text_header) > 0)
					@foreach ($text_header as $text)
						<p>{{ $text }}</p>
					@endforeach
				@else
                    <p>Dostavljamo pristupne podatke za PORTAL ZA ZAPOSLENIKE</p>
                @endif
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="body" style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_body : '' !!}">
                @if(count($text_body) > 0)
					@foreach ($text_body as $text)
						<p>{{ $text }}</p>
					@endforeach
				@else
                    <p>Pristupni podaci:</p>
                    <p>korisničko ime: {{ $user->email }}</p>
                    <p>lozinka: {{ $password}}</p>
                    <p>Obavezno pročitajte Radne upute koje se nalaze Portalu zaposlenika na linku "Radne upute" koje sadrže osnovne informacije i obaveze za svakog zaposlenika tvrtke Duplico.</p>
                    <p>Nakon prvog pristupa stranici obavezno promijenite lozinku.</p>
                    <p>Svoje pristupne podatke nemojte odavati drugiom osobama.</p>
                    <p>Upute za korištenje možete naći na Portalu klikom na link "Dokumenti"</p>
                    <p>Za sva pitanja javite se na email {{ $podrska }}</p>
                    <p>Poralu pristupate putem slijedećeg linka</p>
                    <button href="{{ $link }}" class="odobri">MyIntranet</button>
                @endif
            </div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="footer"  style="{!! $template_mail && $template_mail->mailStyle->first() ? $template_mail->mailStyle->first()->style_footer : '' !!}">
				@if(count($text_footer) > 0)
					@foreach ($text_footer as $text)
						<p>{{ $text }}</p>
					@endforeach
                @endif
                if(file_exists('../public/storage/company_img/logo.png'))
					<img src="{{ URL::asset('storage/company_img/logo.png')}}" alt="company_logo" class="company_logo"/>
				@else
					<p>{{ config('app.name') }}</p>
				@endif
            </div>
        </div>
	</body>
</html>