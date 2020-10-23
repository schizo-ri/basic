@component('mail::message')
# Dostavljamo pristupne podatke za PORTAL ZA ZAPOSLENIKE

@component('mail::panel')

# Pristupni podaci:
<br>
korisničko ime: {{ $user->email }}
<br>
lozinka: {{ $password}}
<br>
<br>
Obavezno pročitajte Radne upute koje se nalaze Portalu zaposlenika na linku "Radne upute" koje sadrže osnovne informacije i obaveze za svakog zaposlenika tvrtke Duplico.
<br>
<br>
Nakon prvog pristupa stranici obavezno promijenite lozinku.
<br>
<br>
Svoje pristupne podatke nemojte odavati drugiom osobama.
<br>
<br>
Upute za korištenje možete naći na Portalu klikom na link "Dokumenti"
<br>
<br>
Za sva pitanja javite se na email {{ $podrska }}
<br>
@endcomponent

Poralu pristupate putem slijedećeg linka
@component('mail::button', ['url' => $link])
MyIntranet
@endcomponent

<br>
{{ config('app.name') }}
@endcomponent