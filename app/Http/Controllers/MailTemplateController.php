<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\MailTemplate;
use App\Models\MailStyle;
use App\Models\MailText;
use Log;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;

class MailTemplateController extends Controller
{
    /**
     * Set middleware to quard controller.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('sentinel.auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $mail_templates = MailTemplate::get();
        $permission_dep = DashboardController::getDepartmentPermission();

        return view('Centaur::mail_templates.index', ['mail_templates' => $mail_templates,'permission_dep' => $permission_dep ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $elements = array('header', 'body','footer');
        $child_elements = array('input');

        $path = '../app/Mail';
        $docs = '';
        if(file_exists($path)){
            $docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
        }

        return view('Centaur::mail_templates.create', ['elements' => $elements, 'child_elements' => $child_elements,'docs' => $docs]);
    }

    public function mailTest ($id)
    {
        $template_mail = MailTemplate::find($id); 

        Mail::to('jelena.juras@duplico.hr')->send(new TestMail( $template_mail ));

        /* session()->flash('success',__('ctrl.email_send')); */
		
       /*  return redirect()->back(); */
       return __('ctrl.email_send');
    } 

    public function create_style (Request $request) 
    {
        return view('Centaur::mail_templates.create_style',['element' => $request['element'], 'count_input' => $request['count_input'] ]);
    }

    public function edit_style (Request $request) 
    {
        return view('Centaur::mail_templates.edit_style',['element' => $request['element'], 'count_input' => $request['count_input'] ]);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $mailTemplate = MailTemplate::orderBy('created_at','DESC')->where('for_mail', $request['for_mail'])->first();
        
        // Tempalte maila
            $data = array(
                'name'          => $request['name'],
                'description'   => $request['description'],
                'for_mail'      => $request['for_mail'],
            );
            
            if( $mailTemplate ) {
                $mailTemplate->updateMailTemplate($data);
            } else {
                $mailTemplate = new MailTemplate();
                $mailTemplate->saveMailTemplate($data);
            }
        // Kraj Tempalte maila 

        if($mailTemplate) {
            // Stil maila
                $mailStyle = $mailTemplate->mailStyle;

                $elements = array('header', 'body','footer');
                $style = array();
                foreach( $elements as $element ) {
                    $style_text = '';
                    foreach(  $request[ $element ] as $key => $value ) {
                        $style_text .= ($key . ':' . $value.';');
                    }
                    $style[ $element ] = $style_text;
                }

                // stil input polja
                $input_elements = array('header_input', 'body_input','footer_input');
                $style_input = array();
               
                foreach( $input_elements as $key => $inputs ) {
                    $value_input = array();
                    foreach(  $request[ $inputs ] as $key_input => $input ) {
                        $style_text = '';
                        foreach( $input as $key_value => $value  ) {
                            $style_text .= ($key_value . ':' . $value.';');
                        }
                        $value_input[$key_input] = $style_text;
                    }
                    $style_input[ $inputs ] = implode( '|', $value_input);
                }

                $data_style = array(
                    'mail_id'       => $mailTemplate->id,
                    'style_header'  => $style['header'] ? $style['header'] : null,
                    'style_body'    => $style['body'] ? $style['body'] : null,
                    'style_footer'  => $style['footer'] ? $style['footer'] : null,
                    'style_header_input'  => $style_input['header_input'] ? $style_input['header_input'] : null,
                    'style_body_input'    => $style_input['body_input'] ? $style_input['body_input'] : null,
                    'style_footer_input'  => $style_input['footer_input'] ? $style_input['footer_input'] : null,
                );

                if(  $mailStyle ) {
                    $mailStyle->updateMailStyle($data_style);
                } else {
                    $mailStyle = new MailStyle();
                    $mailStyle->saveMailStyle($data_style);
                }
            // Kraj Stil maila

            // Tekst maila
                $mailText = $mailTemplate->mailText;

                $text_elements = array('text_header', 'text_body','text_footer');
                $text = array();
                foreach( $text_elements as $element ) {
                    $element_text = '';
                    $count = 0;
                    foreach(  $request[ $element ] as $key => $value ) {
                        foreach(  $value as $key2 => $p_element ) {
                            if($p_element) {
                                $element_text .= ($count . ':' . $p_element.';');
                                $count++;
                            }
                        }
                    }
                    $text[ $element ] = $element_text;
                }

                $data_text = array(
                    'mail_id'       => $mailTemplate->id,
                    'text_header'  => $text['text_header'] ? $text['text_header'] : null,
                    'text_body'    => $text['text_body'] ? $text['text_body'] : null,
                    'text_footer'  => $text['text_footer'] ? $text['text_footer'] : null,
                );

                if ( $mailText ) {
                    $mailText->updateMailText($data_text);
                } else {
                    $mailText = new MailText();
                    $mailText->saveMailText($data_text);
                }
            // Kraj Tekst maila
        }

        session()->flash('success',  __('ctrl.data_save'));
        return redirect()->route('mail_templates.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $mailTemplate = MailTemplate::find($id);
        $elements = array('header', 'body','footer');
        $child_elements = array('input');

        $path = '../app/Mail';
        $docs = '';
        if(file_exists($path)){
            $docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
        }

        // Mail style
            $mailTemplate_style = $mailTemplate->mailStyle;
            $header = array();
            $body = array();
            $footer = array();
            $header_input = array();
            $body_input = array();
            $footer_input = array();
            $header_input_style = array();
            $body_input_style = array();
            $footer_input_style = array();

            if( $mailTemplate_style ) {
                // header
                foreach( explode(';', $mailTemplate_style->style_header) as $header_style) {
                    $temp = explode(':',$header_style);
                    $key = $temp[0];
                    if(isset($temp[1] )) {
                        $val = $temp[1];
                        $header[$key] =$val;
                    }
                }
                $header_input_style =  explode('|', $mailTemplate_style->style_header_input);
                foreach($header_input_style  as $input_style) {
                    $temp = explode(';', $input_style );
                    $temp2 = array();
                    foreach(  $temp as $key => $style) {
                        $temp3 = explode(':', $style);
                        $key = $temp3[0];
                        if(isset($temp3[1] )) {
                            $val = $temp3[1];
                            $temp2[$key] = $val;
                        }
                    }
                    array_push($header_input, $temp2);
                }
                // kraj header

                // body
                foreach( explode(';', $mailTemplate_style->style_body) as $body_style) {
                    $temp = explode(':',$body_style);
                    $key = $temp[0];
                    if(isset($temp[1] )) {
                        $val = $temp[1];
                        $body[$key] =$val;
                    }
                }
                $body_input_style = explode('|', $mailTemplate_style->style_body_input);
                foreach( $body_input_style as $input_style) {
                    $temp = explode(';', $input_style );
                    $temp2 = array();
                    foreach(  $temp as $key => $style) {
                        $temp3 = explode(':', $style);
                        $key = $temp3[0];
                        if(isset($temp3[1] )) {
                            $val = $temp3[1];
                            $temp2[$key] = $val;
                        }
                    }
                    array_push($body_input, $temp2);
                }
                // kraj body

                // footer
                foreach( explode(';', $mailTemplate_style->style_footer) as $footer_style) {
                    $temp = explode(':',$footer_style);
                    $key = $temp[0];
                    if(isset($temp[1] )) {
                        $val = $temp[1];
                        $footer[$key] =$val;
                    }
                }
                $footer_input_style = explode('|', $mailTemplate_style->style_footer_input);
                foreach( $footer_input_style as $input_style) {
                    $temp = explode(';', $input_style );
                    $temp2 = array();
                    foreach(  $temp as $key => $style) {
                        $temp3 = explode(':', $style);
                        $key = $temp3[0];
                        if(isset($temp3[1] )) {
                            $val = $temp3[1];
                            $temp2[$key] = $val;
                        }
                    }
                    array_push($footer_input, $temp2);
                }
                // Kraj footer
            }
            
        // Kraj Mail style

        // Mail text
            $mailTemplate_text = $mailTemplate->mailText;

            $header_text = array();
            $body_text = array();
            $footer_text = array();
            if( $mailTemplate_text ) {
                foreach( explode(';', $mailTemplate_text->text_header) as $text_header) {
                    $temp = explode(':', $text_header);
                
                    $key = $temp[0];
                    if(isset($temp[1] )) {
                        $val = $temp[1];
                        $header_text[$key] = $val;
                    }
                }       
                foreach( explode(';', $mailTemplate_text->text_body) as $text_body) {
                    $temp = explode(':', $text_body);
                
                    $key = $temp[0];
                    if(isset($temp[1] )) {
                        $val = $temp[1];
                        $body_text[$key] = $val;
                    }
                }
                foreach( explode(';', $mailTemplate_text->text_footer) as $text_footer) {
                    $temp = explode(':', $text_footer);
                
                    $key = $temp[0];
                    if(isset($temp[1] )) {
                        $val = $temp[1];
                        $footer_text[$key] = $val;
                    }
                }
            }
        // Mail text

        return view('Centaur::mail_templates.edit', ['mailTemplate' => $mailTemplate,'elements' => $elements,'child_elements' => $child_elements,  'docs' => $docs, 'mailTemplate_style' => $mailTemplate_style, 'header' => $header, 'body' => $body, 'footer' => $footer,'header_input' => $header_input, 'body_input' => $body_input, 'footer_input' => $footer_input,'header_input_style' => $header_input_style, 'body_input_style' => $body_input_style, 'footer_input_style' => $footer_input_style, 'header_text' => $header_text,'body_text' => $body_text,'footer_text' => $footer_text ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $mailTemplate = MailTemplate::find($id);

        $mailStyle =  $mailTemplate->mailStyle;
        $mailText =  $mailTemplate->mailText;

        // Tempalte maila
            $data = array(
                'name'          => $request['name'],
                'description'   => $request['description'],
                'for_mail'      => $request['for_mail'],
            );
            
            $mailTemplate->updateMailTemplate($data);
        // Kraj Tempalte maila

        // Stil maila
            $mailStyle = $mailTemplate->mailStyle;
            $elements = array('header', 'body','footer');
            $style = array();
            foreach( $elements as $element ) {
                $style_text = '';
                foreach(  $request[ $element ] as $key => $value ) {
                    $style_text .= ($key . ':' . $value.';');
                }
                $style[ $element ] = $style_text;
            }

            // stil input polja
            $input_elements = array('header_input', 'body_input','footer_input');
            $style_input = array();
            foreach( $input_elements as $key => $inputs ) {
                $value_input = array();
                foreach(  $request[ $inputs ] as $key_input => $input ) {
                    $style_text = '';
                    foreach( $input as $key_value => $value  ) {
                        $style_text .= ($key_value . ':' . $value.';');
                    }
                    $value_input[$key_input] = $style_text;
                }
                $style_input[ $inputs ] = implode( '|', $value_input);
            }

            $data_style = array(
                'mail_id'       => $mailTemplate->id,
                'style_header'  => $style['header'] ? $style['header'] : null,
                'style_body'    => $style['body'] ? $style['body'] : null,
                'style_footer'  => $style['footer'] ? $style['footer'] : null,
                'style_header_input'  => $style_input['header_input'] ? $style_input['header_input'] : null,
                'style_body_input'    => $style_input['body_input'] ? $style_input['body_input'] : null,
                'style_footer_input'  => $style_input['footer_input'] ? $style_input['footer_input'] : null,
            );

            if(  $mailStyle ) {
                $mailStyle->updateMailStyle($data_style);
            } else {
                $mailStyle = new MailStyle();
                $mailStyle->saveMailStyle($data_style);
            }
        // Kraj Stil maila
        
        // Tekst maila
            $text_elements = array('text_header', 'text_body','text_footer');
            $text = array();
            foreach( $text_elements as $element ) {
                $element_text = '';
                $count = 1;
                foreach(  $request[ $element ] as $key => $value ) {
                    foreach(  $value as $key2 => $p_element ) {
                        if($p_element) {
                            $element_text .= ($count . ':' . $p_element.';');
                            $count++;
                        }
                    }
                }
                $text[ $element ] = $element_text;
            }

            $data_text = array(
                'mail_id'       => $mailTemplate->id,
                'text_header'  => $text['text_header'] ? $text['text_header'] : null,
                'text_body'    => $text['text_body'] ? $text['text_body'] : null,
                'text_footer'  => $text['text_footer'] ? $text['text_footer'] : null,
            );

            if ( $mailText ) {
                $mailText->updateMailText($data_text);
            } else {
                $mailText = new MailText();
                $mailText->saveMailText($data_text);
            }
        // Kraj Tekst maila

        session()->flash('success',  __('ctrl.data_save'));
        return redirect()->route('mail_templates.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mailTemplate = MailTemplate::find($id);
        $mailTemplate->delete();
        
        session()->flash('success',__('ctrl.data_delete'));
		
        return redirect()->back();
    }
}
