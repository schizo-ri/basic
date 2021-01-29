<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'=>':attribute mora biti prihvaćen.',
    'active_url'=>':attribute nije važeći URL.',
    'after'=>':attribute mora biti poslije :date.',
    'after_or_equal'=>' :attribute mora biti datum nakon ili jednak :date.',
    'alpha'=>':attribute može sadržavati samo slova.',
    'alpha_dash'=>':attribute može sadržavati samo slova, brojeve, crtice i donje crte.',
    'alpha_num'=>':attribute može sadržavati samo slova i brojeve.',
    'array'=>':attribute mora biti niz.',
    'before'=>':attribute mora biti datum prije :date.',
    'before_or_equal'=>':attribute must be a date before or equal to :date.',
    'between' => [
        'numeric'=>':attribute mora biti između :min i :max.',
        'file'=>':attribute mora biti između :min i :max kilobyta.',
        'string'=>':attribute mora biti između :min i :max znakova.',
        'array'=>':attribute mora imati između :min i :max stavki.',
    ],
    'boolean'=>':attribute mora biti točno ili netačno.',
    'confirmed'=>':attribute potvrda se ne podudara.',
    'date'=>':attribute nije valjani datum.',
    'date_equals'=>':attribute mora biti datum jednak :date.',
    'date_format'=>':attribute ne odgovara formatu :format.',
    'different'=>':attribute i :other moraju biti različiti.',
    'digits'=>':attribute mora imati :digits znamenki.',
    'digits_between'=>':attribute mora biti između :min i :max znamenki.',
    'dimensions'=>':attribute ima nevažeću dimenzije slike.',
    'distinct'=>'Polje :attribute ima dvostruku vrijednost.',
    'email'=>':attribute mora biti valjana adresa e-pošte.',
    'ends_with'=>':attribute mora završiti s jednom od sljedećih :vrijednosti',
    'exists'=>'Odabrani :atribut nije važeći.',
    'file'=>':attribute mora biti datoteka.',
    'filled'=>'Polje :attribute  mora imati vrijednost.',
    'gt' => [
        'numeric'=>':attribute mora biti veći od: value.',
        'file'=>':attribute mora biti veći od: kilobajta vrijednosti.',
        'string'=>':attribute mora biti veći od: znakova vrijednosti.',
        'array'=>':attribute mora imati više od: vrijednosti stavki.',
    ],
    'gte' => [
        'numeric'=>':attribute mora biti veći ili jednak :value',
        'file'=>':attribute mora biti veći ili jednak :value kilobajta.',
        'string'=>':attribute mora biti veći ili jednak :value vrijednosti.',
        'array'=>':attribute mora imati: vrijednosti stavki ili više.',
    ],
    'image'=>':attribute mora biti slika.',
    'in'=>'Odabrani :attribute nije važeći.',
    'in_array'=>'Polje :attribute ne postoji u :other.',
    'integer'=>':attribute mora biti cijeli broj.',
    'ip'=>':attribute mora biti valjana IP adresa.',
    'ipv4'=>':attribute mora biti valjana IPv4 adresa.',
    'ipv6'=>':attribute mora biti valjana IPv6 adresa.',
    'json'=>':attribute mora biti važeći JSON niz.',
    'lt' => [
        'numeric'=> ':attribute mora biti manji od :value.',
        'file'  => ':attribute atribut mora biti manji od :value kilobytes.',
        'string'=> ':attribute atribut mora biti manji od :value znakova.',
        'array' => ':attribute atribut mora imati manje od: value items.',
    ],
    'lte' => [
        'numeric'=>':attribute mora biti manji ili jednak <:value.',
        'file'=>':attribute  mora biti manji ili jednak :value kilobajta.',
        'string'=>':attribute mora biti manji ili jednak :value znakova.',
        'array'=>':attribute ne smije imati više od :value dijelova.',
    ],
    'max' => [
        'numeric'=>'Vrijednost polja :attribute ne smije biti veći od :maks.',
        'file'=>':attribute ne smije biti veći od: max kilobajta.',
        'string'=>'Polje :attribute  ne smije biti veći od: max znakova.',
        'array'=>':attribute atribut ne smije imati više od: max stavki.',
    ],
    'mimes'=>':attribute mora biti datoteka tipa :values.',
    'mimetypes'=>':attribute mora biti datoteka tipa :values.',
    'min' => [
        'numeric'=>':attribute mora biti najmanje :min.',
        'file'=>':attribute atribut mora biti najmanje :min kilobajta.',
        'string'=>'Polje :attribute mora sadržavati najmanje :min znakova',
        'array'=>':attribute mora imati najmanje :min stavki.',
    ],
    'not_in'=>'Odabrani :attribute nije važeći.',
    'not_regex'=>'Format :attribute nije važeći.',
    'numeric'=>':attribute mora biti između :min i :max.',
    'present'=>'Polje :attribute mora biti prisutno.',
    'regex'=>'Format :attribute nije važeći.',
    'required'=>'Polje :attribute je obavezno.',
    'required_if'=>'Polje :attribute je obavezno kada je :other :value.',
    'required_unless'=>'Polje :attribute je obavezno, osim ako je :other u :values.',
    'required_with'=>'Polje: attribute obavezno je kada su prisutne :values.',
    'required_with_all'=>'The :attribute field is required when :values are present.',
    'required_without'=>'Polje: atribut je obavezno kada nisu prisutne vrijednosti.',
    'required_without_all'=>'Polje :attribute je obavezno kada nije prisutna nijedna od :values.',
    'same'=>':attribute  i :other moraju se podudarati.',
    'size' => [
        'numeric'=>':attribute mora biti :size.',
        'file'=>':attribute mora biti  :size kilobytes.',
        'string'=>':attribute mora imati :size znakova.',
        'array'=>':attribute mora sadržavati :size stavaka',
    ],
    'starts_with'=>':attribute mora započeti s jednom od sljedećih :vrijednosti',
    'string'=>':attribute mora biti tekst',
    'timezone'=>':attribute mora biti važeća zona.',
    'unique'=>':attribute već je zauzet.',
    'uploaded'=>':attribute prijenos nije uspio.',
    'url'=>':attribute format nije važeći.',
    'uuid'=>':attribute mora biti važeći UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
