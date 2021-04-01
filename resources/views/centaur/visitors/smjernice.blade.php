<h5>Poštovani posjetitelji,</h5>
<p>
    dobrodošli u tvrtku Duplico! Jedna od temeljnih vrijednosti i prioriteta naše tvrtke je briga o zdravlju i zaštiti osoba na radu i posjetitelja, kroz sustave upravljanja kvalitetom, okolišem, zdravljem i sigurnosti na radu prema međunarodnim normama. Cilj naše tvrtke je osigurati maksimalnu sigurnost svim osobama prisutnima na lokaciji tvrtke, stoga Vas molimo da u nastavku upišete tražene podatke u svrhu evidencije Vašeg ulaska i boravka u tvrtki te pažljivo pročitate Upute za sigurnost posjetitelja i da ih se pridržavate tijekom posjete.
</p>

<form accept-charset="UTF-8" role="form" class="visitor_form" method="post" action="{{ route('visitors.store') }}">
    <section class="visitors_section">
        <div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }} ">
            <input class="form-control" placeholder="Ime" name="first_name" type="text" maxlength="20" value="{{ old('first_name') }}" autofocus/>
            {!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="Prezime" name="last_name" type="text" maxlength="20" value="{{ old('last_name') }}" />
            {!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="E-mail" name="email" type="text" maxlength="50" value="{{ old('email') }}">
            {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('company')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="Tvrtka" name="company" type="text" maxlength="50" value="{{ old('company') }}">
            {!! ($errors->has('company') ? $errors->first('company', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <input class="form-control" name="lang" type="hidden" value="hr">
    </section>
    <section class="visitors_section">
        <h5><b>Posjetitelji su dužni pridržavati se sljedećih uputa:</b></h5>
        <p><img class="icon_alert" src="{{ asset('icons/icon_forbidden.jpg') }}" /> zabranjen je pristup mjestima i ulaz u prostorije na kojima posjet nije predviđen </p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_stop.jpg') }}" /> zabranjeno je zadržavanje u blizini opreme i strojeva u radu, bez nadzora</p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_smoke.jpg') }}" /> zabranjeno je pušenje u prostorijama tvrtke</p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_food.jpg') }}" /> zabranjeno unošenje i konzumiranje hrane i pića</p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_photo.jpg') }}" /> zabranjeno fotografiranje i snimanje bez izričitog, pismenog odobrenja ovlaštene osobe</p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_escort.jpg') }}" /> posjetitelji su obvezni biti uvijek u pratnji domaćina i slijediti njegove upute</p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_glove.jpg') }}" /> ukoliko je obvezno, nositi odgovarajuću zaštitnu opremu koju osigurava domaćin</p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_garbage.jpg') }}" /> otpad odložiti isključivo u za to predviđene posude obraćajući pažnju na odvajanje otpada po vrstama, ukoliko je to omogućeno</p>
        <b></b>
        <h5><b>Nepridržavanje gore navedenih uputa može dovesti do potencijalnih nezgoda i ozljeda te zabrane ulaska u tvrtku.</b></h5>
    </section>
    <div class="alert_box video visitors_section">
        <span class="alert_icons">
            <img class="icon_alert" src="{{ asset('icons/icon_video.jpg') }}" /> 
        
        </span>
        <p class="alert_text">Prostorije tvrtke i parkiralište nalaze se pod videonadzorom iz sigurnosnih razloga </p>
    </div>
    <section class="visitors_section">
        <h5><b>Mjere zaštite od virusa COVID-19</b></h5>
        <p>S obzirom na globalnu epidemiološku situaciju s virusom COVID-19, molimo Vas da se za vrijeme boravka u tvrtki Duplico pridržavate niže navedenih mjera, kako bismo i Vas i sve zaposlenike tvrtke Duplico zaštitili od potencijalne zaraze virusom:</p>
        <ul class="">
            <li>Izbjegavajte kontakt s osobama s kojima kontakt nije nužan</li>
            <li>Prilikom komunikacije i zajedničkog rada održavati razmak od minimalno 1 metar</li>
            <li>Koristite zaštitne maske i rukavice</li>
            <li>Izbjegavati rukovanje te što češće prati ruke i koristiti dezinfekcijska sredstva na bazi alkohola</li>
            <li>Kada kašljete ili kišete prekrijte usta i nos rukom ili maramicom koju poslije odbacite u koš za otpad te operite ruke</li>
            <li>Izbjegavajte dodirivanje lica, nosa i očiju</li>
            <li>Koristite isključivo WC koji se nalazi u prizemlju u radioni</li>
            <li>U slučaju da vam je potrebna okrijepa (voda, kava i sl.), molimo Vas kontaktirajte tajnicu na prvom katu poslovne zgrade</li>
            <li>U slučaju pojave bilo kakvih simptoma zaraženosti virusom COVID-19 ili ukoliko imate saznanja da ste bili u kontaktu s osobom koja ima simptome ili je zaražena virusom, molimo Vas da bez odgode o tome obavijestite osobu koja je Vaš domaćin i napustite prostorije tvrtke</li>
        </ul>
    </section>
    <section class="visitors_section">
        <h5><b>Evakuacija</b></h5>
        <div class="alert_box">
            <span class="alert_icons">
                <img class="icon_alert" src="{{ asset('icons/icon_out.jpg') }}" /> 
                <img class="icon_alert" src="{{ asset('icons/icon_right.jpg') }}" /> 
                <img class="icon_alert" src="{{ asset('icons/icon_left.jpg') }}" /> 
            </span>
            <p class="alert_text"> U slučaju bilo kakvog izvanrednog  događaja ostanite smireni i slijedite upute koje ćete dobiti 
                od zaposlenika tvrtke Duplico te u slučaju potrebe za evakuacijom slijedite znakove za izlaz i niže priloženeplanove evakuacije.</p>
        </div>

        <a href="{{ asset('icons/visitors/hr/Plan_evakuacije_pr.png') }}" class="evacuation_plan">
            <img src="{{ asset('icons/visitors/hr/Plan_evakuacije_pr.png') }}" style="max-width:100%" />
        </a>
        <a href="{{ asset('icons/visitors/hr/Plan_evakuacije_1.png') }}" class="evacuation_plan">
            <img src="{{ asset('icons/visitors/hr/Plan_evakuacije_1.png') }}" style="max-width:100%" />
        </a>
        <a href="{{ asset('icons/visitors/hr/Plan_evakuacije_2.png') }}" class="evacuation_plan">
            <img src="{{ asset('icons/visitors/hr/Plan_evakuacije_2.png') }}" style="max-width:100%" />
        </a>
        <a href="{{ asset('icons/visitors/hr/Plan_evakuacije_h.png') }}" class="evacuation_plan">
            <img src="{{ asset('icons/visitors/hr/Plan_evakuacije_h.png') }}" style="max-width:100%" />
        </a>
        <a href="{{ asset('icons/visitors/hr/Plan_evakuacije_h_prolaz.png') }}" class="evacuation_plan">
            <img src="{{ asset('icons/visitors/hr/Plan_evakuacije_h_prolaz.png') }}" style="max-width:100%" />
        </a>
        <a href="{{ asset('icons/visitors/hr/Plan_evakuacije_h2.png') }}" class="evacuation_plan">
            <img src="{{ asset('icons/visitors/hr/Plan_evakuacije_h2.png') }}" style="max-width:100%" />
        </a>
    </section>   
    <div class="form-group smjernice visitors_section">
        <div class="{{ ($errors->has('accept')) ? 'has-error' : '' }} ">
            <label>
                <input name="accept" type="checkbox" value="1" {{ old('accept') == 'checked' ? 'checked' : ''}} required > <b>Potvrđujem da sam pročitao/la, da razumijem i prihvaćam upute za sigurnost posjetitelja!</b>
            </label>
            {!! ($errors->has('accept') ? $errors->first('accept', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="{{ ($errors->has('confirm')) ? 'has-error' : '' }} ">
            <label>
                <input name="confirmed" type="checkbox" value="1" {{ old('confirm') == 'true' ? 'checked' : ''}} required > <b>Potvrđujem da sam preuzeo/la i da sam upoznat s načinom korištenja ključa za ulazak u prostorije tvrtke </b>
            </label>
            {!! ($errors->has('confirm') ? $errors->first('confirm', '<p class="text-danger">:message</p>') : '') !!}
        </div>
    </div>
    <input class="form-control" name="card_id" type="hidden" maxlength="20" value="{!! isset($card_id) ? $card_id : 1 !!}">
    {{ csrf_field() }}
    <input class="btn-submit btn_submit_reg" type="submit" value="Potvrda"> 
</form>