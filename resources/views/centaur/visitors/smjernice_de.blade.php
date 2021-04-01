<h5>Sehr gehrte Besucher,</h5>
<p>
    Wilkommen bei Duplico! Einer der zentralen Werte und Prioritäten unseres Unternehmens ist die Gewährleistung der Gesundheit und Sicherheit von Arbeitnehmern und Besuchern durch Qualitäts-, Umwelt-, Gesundheits- und Sicherheitsmanagementsysteme gemäß internationalen Standards.
    Das Ziel unseres Unternehmens ist es, allen Anwesenden am Unternehmensstandort ein Höchstmaß an Sicherheit zu bieten. Geben Sie daher die erforderlichen Informationen ein, um Ihren Aufenthalt im Unternehmen zu protokollieren und die Besuchersicherheitsrichtlinien sorgfältig zu lesen und bei Ihrem Besuch einzuhalten.
</p>
<form accept-charset="UTF-8" role="form" class="visitor_form" method="post" action="{{ route('visitors.store') }}">
    <section class="visitors_section">
        <div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="Name" name="first_name" type="text" maxlength="20" value="{{ old('first_name') }}" autofocus/>
            {!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="Nachname" name="last_name" type="text" maxlength="20" value="{{ old('last_name') }}" />
            {!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="E-mail" name="email" type="text" maxlength="50" value="{{ old('email') }}">
            {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('company')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="Firma" name="company" type="text" maxlength="50" value="{{ old('company') }}">
            {!! ($errors->has('company') ? $errors->first('company', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <input class="form-control" name="lang" type="hidden" value="de">
    </section>
    <section class="visitors_section">
        <h5><b>Besucher sind verpflichtet, Folgendes zu beachten:</b></h5>
        <p><img class="icon_alert" src="{{ asset('icons/icon_forbidden.jpg') }}" /> Der Zugang zu Orten und Räumen, in denen kein Besuch geplant ist, ist verboten</p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_stop.jpg') }}" /> Es ist verboten, sich ohne Aufsicht in der Nähe von Geräten und Maschinen aufzuhalten, die in Betrieb sind</p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_smoke.jpg') }}" /> Das Rauchen auf dem Firmengelände ist verboten</p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_food.jpg') }}" /> Es ist verboten, Essen und Trinken mitzubringen und zu konsumieren</p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_photo.jpg') }}" /> Es ist verboten, Fotos und Aufzeichnungen ohne die ausdrückliche schriftliche Genehmigung einer autorisierten Person aufzunehmen</p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_escort.jpg') }}" /> Besucher müssen immer vom Gastgeber begleitet werden und seine Anweisungen befolgen</p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_glove.jpg') }}" /> Falls benötig,tragen Sie eine vom Gastgeber bereitgestellte Schutzausrüstung</p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_garbage.jpg') }}" /> Entsorgen Sie Abfälle nur in dafür vorgesehenen Behälter, wobei Sie nach Möglichkeit auf die Trennung der Abfälle achten.</p>
    
        <h5><b>Die Nichtbeachtung der oben genannten Anweisungen kann zu möglichen Unfällen und Verletzungen sowie zu einem Verbot des Eintritts in das Unternehmen führen.</b></h5>
    </section>
    <div class="alert_box video visitors_section">
        <span class="alert_icons">
            <img class="icon_alert" src="{{ asset('icons/icon_video.jpg') }}" /> 
        </span>
        <p class="alert_text">Die Räumlichkeiten und der Parkplatz des Unternehmens werden aus Sicherheitsgründen einer Videoüberwachung unterzogen</p>
    </div>
    <section class="visitors_section">
        <h5><b>COVID-19-Schutzmaßnahmen</b></h5>
        <p>Angesichts der globalen epidemiologischen Situation mit dem COVID-19-Virus bitten wir sie während Ihres Aufenthalts bei Duplico  folgenden Maßnahmen zu beachten  um Sie und alle Mitarbeiter von Duplico vor einer möglichen Infektion mit dem Virus zu schützen:</p>
        <ul class="">
            <li>Vermeiden Sie  Kontakt mit Personen mit denen er nicht erforderlich ist</li>
            <li>Halten Sie bei der Kommunikation und Zusammenarbeit einen Abstand von mindestens 1 Meter ein</li>
            <li>Tragen Sie Schutzmasken und Handschuhe</li>
            <li>Vermeiden Sie Händeschüttelln und waschen Sie Ihre Hände so oft wie möglich und verwenden Desinfektionsmittel auf Alkoholbasis</li>
            <li>Wenn Sie husten oder niesen, bedecken Sie Mund und Nase mit Ihrer Hand oder einem Taschentuch, das Sie dann in den Müll werfen und Ihre Hände waschen</li>
            <li>Vermeiden Sie Gesicht, Nase und Augen zu berühren</li>
            <li>Verwenden Sie nur die Toilette im Erdgeschoss der Werkstatt</li>
            <li>Wenn Sie Erfrischungen benötigen (Wasser, Kaffee usw.), wenden Sie sich bitte an die Sekretärin im ersten Stock des Bürogebäudes</li>
            <li>Bei Symptomen einer COVID-19-Infektion oder wenn Sie wissen, dass Sie mit einer Person in Kontakt gekommen sind, die Symptome hat oder mit dem Virus infiziert ist, informieren Sie bitte Ihren Gastgeber unverzüglich und verlassen  das Firmengelände.</li>
        </ul>
    </section>
    <section class="visitors_section">
        <h5><b>Evakuierung</b></h5>
        
        <div class="alert_box">
            <span class="alert_icons">
                <img class="icon_alert" src="{{ asset('icons/icon_out.jpg') }}" /> 
                <img class="icon_alert" src="{{ asset('icons/icon_right.jpg') }}" /> 
                <img class="icon_alert" src="{{ asset('icons/icon_left.jpg') }}" /> 
            </span>
            <p class="alert_text">Bleiben Sie im Notfall ruhig und befolgen Anweisungen, die Sie von Duplico-Mitarbeitern erhalten und im Evakuierungsbedarf folgen sie den Ausgangsschildern so wie den beigefügten Evakuierungsplänen. </p>
        </div>

        <a href="{{ asset('icons/visitors/de/Plan_evakuacije_pr.png') }}" class="evacuation_plan">
            <img src="{{ asset('icons/visitors/de/Plan_evakuacije_pr.png') }}" style="max-width:100%" />
        </a>
        <a href="{{ asset('icons/visitors/de/Plan_evakuacije_1.png') }}" class="evacuation_plan">
            <img src="{{ asset('icons/visitors/de/Plan_evakuacije_1.png') }}" style="max-width:100%" />
        </a>
        <a href="{{ asset('icons/visitors/de/Plan_evakuacije_2.png') }}" class="evacuation_plan">
            <img src="{{ asset('icons/visitors/de/Plan_evakuacije_2.png') }}" style="max-width:100%" />
        </a>
        <a href="{{ asset('icons/visitors/de/Plan_evakuacije_h.png') }}" class="evacuation_plan">
            <img src="{{ asset('icons/visitors/de/Plan_evakuacije_h.png') }}" style="max-width:100%" />
        </a>
        <a href="{{ asset('icons/visitors/de/Plan_evakuacije_h_prolaz.png') }}" class="evacuation_plan">
            <img src="{{ asset('icons/visitors/de/Plan_evakuacije_h_prolaz.png') }}" style="max-width:100%" />
        </a>
        <a href="{{ asset('icons/visitors/de/Plan_evakuacije_h2.png') }}" class="evacuation_plan">
            <img src="{{ asset('icons/visitors/de/Plan_evakuacije_h2.png') }}" style="max-width:100%" />
        </a>
    </section>
    <div class="form-group smjernice visitors_section">
        <div class="{{ ($errors->has('accept')) ? 'has-error' : '' }} ">
            <label>
                <input name="accept" type="checkbox" value="1" {{ old('accept') == 'true' ? 'checked' : ''}} > <b>Hiermit bestätige ich, dass ich die Hinweise zur Besuchersicherheit gelesen, verstanden und akzeptiert habe!</b>
            </label>
            {!! ($errors->has('accept') ? $errors->first('accept', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="{{ ($errors->has('confirm')) ? 'has-error' : '' }} ">
            <label>
                <input name="confirm" type="checkbox" value="1" {{ old('confirm') == 'true' ? 'checked' : ''}} > <b>Hiermit bestätige ich das ich das ich den Schlüssel übernommen habe und mir die Benutzung bekannt ist.
                </b>
            </label>
            {!! ($errors->has('confirm') ? $errors->first('confirm', '<p class="text-danger">:message</p>') : '') !!}
        </div>
    </div>								
    <input class="form-control" name="card_id" type="hidden" maxlength="20" value="{!! isset($card_id) ? $card_id : 1 !!}">	
    {{ csrf_field() }}
    <input class="btn-submit btn_submit_reg" type="submit" value="Bestätigung"> 
</form>
