<h5>Dear visitors,</h5>
<p>
    welcome to Duplico! One of the core values and priorities of our company is the care for the health and safety of workers and visitors, through quality, environmental, health and safety management systems in accordance with international standards. The goal of our company is to provide maximum security to all those present at the company location, so please enter the required information below for the purpose of recording your entry and stay at the company and to read carefully the Visitor Safety Instructions and to abide by them during your visit.
</p>
<form accept-charset="UTF-8" role="form" class="visitor_form" method="post" action="{{ route('visitors.store') }}">
    <section class="visitors_section">
        <div class="form-group {{ ($errors->has('first_name')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="First name" name="first_name" type="text" maxlength="20" value="{{ old('first_name') }}" autofocus/>
            {!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('last_name')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="Last name" name="last_name" type="text" maxlength="20" value="{{ old('last_name') }}" />
            {!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('email')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="E-mail" name="email" type="text" maxlength="50" value="{{ old('email') }}">
            {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="form-group {{ ($errors->has('company')) ? 'has-error' : '' }}">
            <input class="form-control" placeholder="Company" name="company" type="text" maxlength="50" value="{{ old('company') }}">
            {!! ($errors->has('company') ? $errors->first('company', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <input class="form-control" name="lang" type="hidden" value="en">
    </section>
    <section class="visitors_section">
        <h5><b>Visitors are obliged to adhere to the following: </b></h5>
        <p><img class="icon_alert" src="{{ asset('icons/icon_forbidden.jpg') }}" />	access to places and entrance to the space to which the visitor is not intended is prohibited</p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_stop.jpg') }}" /> it is forbidden to stay in the vicinity of equipment and machines in operation, without supervision</p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_smoke.jpg') }}" />	smoking is prohibited on company premises</p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_food.jpg') }}" /> it is forbidden to intake and consume food and beverages</p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_photo.jpg') }}" /> it is forbidden to take photographs and recordings without the express, written consent of an authorized person</p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_escort.jpg') }}" /> visitors are required to be accompanied by the host and follow his instructions</p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_glove.jpg') }}" /> if required, wear appropriate protective equipment provided by the host</p>
        <p><img class="icon_alert" src="{{ asset('icons/icon_garbage.jpg') }}" /> dispose of waste only in containers provided for this purpose, paying attention to the separation of waste by type, if possible</p>

        <h5><b>Failure to follow these instructions can lead to potential accidents and injuries and a ban on entering the company.</b></h5>
    </section>
    <div class="alert_box video visitors_section">
        <span class="alert_icons">
            <img class="icon_alert" src="{{ asset('icons/icon_video.jpg') }}" /> 
        
        </span>
        <p class="alert_text">The company's premises and parking lot are under video surveillance for security reasons</p>
    </div>
    <section class="visitors_section">
        <h5><b>COVID-19 virus protection measures</b></h5>
        <p>Given the global epidemiological situation with the COVID-19 virus, please adhere to the following measures during your stay at Duplico, in order to protect you and all Duplico employees from potential infection with the virus:</p>
        <ul class="">
            <li>Avoid contact with people with whom contact is not necessary</li>
            <li>When communicating and working together, keep a distance of at least 1 meter</li>
            <li>Wear protective masks and gloves</li>
            <li>Avoid handling and wash your hands as often as possible and use alcohol-based disinfectants</li>
            <li>When you cough or sneeze, cover your mouth and nose with your hand or a handkerchief, which you then throw in the trash and wash your hands</li>
            <li>Avoid touching your face, nose and eyes</li>
            <li>Use only the toilet located on the ground floor of the workshop</li>
            <li>In case you need refreshments (water, coffee, etc.), please contact the secretary on the first floor of the office building</li>
            <li>In the event of any symptoms of COVID-19 infection or if you know that you have been in contact with a person who has symptoms or is infected with the virus, please inform your host without delay and leave the company's premises.</li>
        </ul>
    </section>
    <section class="visitors_section">
        <h5><b>Evacuation</b></h5>
        <div class="alert_box">
            <span class="alert_icons">
                <img class="icon_alert" src="{{ asset('icons/icon_out.jpg') }}" /> 
                <img class="icon_alert" src="{{ asset('icons/icon_right.jpg') }}" /> 
                <img class="icon_alert" src="{{ asset('icons/icon_left.jpg') }}" /> 
            </span>
            <p class="alert_text">In case of any emergency, stay calm and follow the instructions you will receive from Duplico employees and in case of a need for evacuation follow the exit signs and the attached evacuation plans below.</p>
        </div>

        <a href="{{ asset('icons/visitors/en/Plan_evakuacije_pr.png') }}" class="evacuation_plan">
            <img src="{{ asset('icons/visitors/en/Plan_evakuacije_pr.png') }}" style="max-width:100%" />
        </a>
        <a href="{{ asset('icons/visitors/en/Plan_evakuacije_1.png') }}" class="evacuation_plan">
            <img src="{{ asset('icons/visitors/en/Plan_evakuacije_1.png') }}" style="max-width:100%" />
        </a>
        <a href="{{ asset('icons/visitors/en/Plan_evakuacije_2.png') }}" class="evacuation_plan">
            <img src="{{ asset('icons/visitors/en/Plan_evakuacije_2.png') }}" style="max-width:100%" />
        </a>
        <a href="{{ asset('icons/visitors/en/Plan_evakuacije_h.png') }}" class="evacuation_plan">
            <img src="{{ asset('icons/visitors/en/Plan_evakuacije_h.png') }}" style="max-width:100%" />
        </a>
        <a href="{{ asset('icons/visitors/en/Plan_evakuacije_h_prolaz.png') }}" class="evacuation_plan">
            <img src="{{ asset('icons/visitors/en/Plan_evakuacije_h_prolaz.png') }}" style="max-width:100%" />
        </a>
        <a href="{{ asset('icons/visitors/en/Plan_evakuacije_h2.png') }}" class="evacuation_plan">
            <img src="{{ asset('icons/visitors/en/Plan_evakuacije_h2.png') }}" style="max-width:100%" />
        </a>
    </section>
    <div class="form-group smjernice visitors_section">
        <div class="{{ ($errors->has('accept')) ? 'has-error' : '' }} ">
            <label>
                <input name="accept" type="checkbox" value="1" {{ old('accept') == 'true' ? 'checked' : ''}} > <b>I hereby confirm that I have read, understood and accepted the Visitors Safety Instructions</b>
            </label>
            {!! ($errors->has('accept') ? $errors->first('accept', '<p class="text-danger">:message</p>') : '') !!}
        </div>
        <div class="{{ ($errors->has('confirm')) ? 'has-error' : '' }} ">
            <label>
                <input name="confirm" type="checkbox" value="1" {{ old('confirm') == 'true' ? 'checked' : ''}} > <b>I hereby confirm that I have taken over and that I am familiar with how to use the key to enter the  
                    company premises
                </b>
            </label>
            {!! ($errors->has('confirm') ? $errors->first('confirm', '<p class="text-danger">:message</p>') : '') !!}
        </div>
    </div>

    <input class="form-control" name="card_id" type="hidden" maxlength="20" value="{!! isset($card_id) ? $card_id : 1 !!}">			
    {{ csrf_field() }}
    <input class="btn-submit btn_submit_reg" type="submit" value="Confirmation">
</form>