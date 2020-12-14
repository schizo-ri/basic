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

        $path = '../app/Mail';
        $docs = '';
        if(file_exists($path)){
            $docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
        }

        return view('Centaur::mail_templates.create', ['elements' => $elements, 'docs' => $docs]);
    }

    public function mailTest ($id)
    {
        $template_mail = MailTemplate::where('for_mail', 'AbsenceMail')->first(); 

        Mail::to('jelena.juras@duplico.hr')->send(new TestMail($template_mail ));

        /* session()->flash('success',__('ctrl.email_send')); */
		
       /*  return redirect()->back(); */
       return __('ctrl.email_send');
    } 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     /*    dd($request); */
        $data = array(
			'name'          => $request['name'],
			'description'   => $request['description'],
			'for_mail'      => $request['for_mail'],
		);
		
		$mailTemplate = new MailTemplate();
        $mailTemplate->saveMailTemplate($data);
       
        $elements = array('header', 'body','footer');
        $style = array();
        foreach( $elements as $element ) {
            $style_text = '';
            foreach(  $request[ $element ] as $key => $value ) {
                $style_text .= ($key . ':' . $value.';');
            }
            $style[ $element ] = $style_text;
        }
     
        $data_style = array(
            'mail_id'       => $mailTemplate->id,
            'style_header'  => $style['header'],
            'style_body'    => $style['body'],
            'style_footer'  => $style['footer'],
        );

        $mailStyle = new MailStyle();
        $mailStyle->saveMailStyle($data_style);

        $elements2 = array('text_header', 'text_body','text_footer');
        $text = array();
        foreach( $elements2 as $element ) {
            $text_mail = '';
            if(isset( $request[ $element ]) ) {
                foreach( $request[ $element ] as $key => $value ) {
                    $text_mail .= ($key . ':' . $value.';');
                }
                $text[ $element ] = $text_mail;
            }
        }

        $data_text = array(
            'mail_id'       => $mailTemplate->id,
            'text_header'  => isset($text['text_header']) ? $text['text_header'] : null,
            'text_body'    => isset($text['text_body']) ? $text['text_body'] : null,
            'text_footer'  => isset($text['text_footer']) ? $text['text_footer'] : null,
        );
      
        $mailText = new MailText();
        $mailText->saveMailText($data_text);
        
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

        $path = '../app/Mail';
        $docs = '';
        if(file_exists($path)){
            $docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
        }

        $mailTemplate_style = $mailTemplate->mailStyle;

        $header = array();
        foreach( explode(';', $mailTemplate_style->style_header) as $header_style) {
            $temp = explode(':',$header_style);
            $key = $temp[0];
            if(isset($temp[1] )) {
                $val = $temp[1];
                $header[$key] =$val;
            }
        }
        $body = array();
        foreach( explode(';', $mailTemplate_style->style_body) as $body_style) {
            $temp = explode(':',$body_style);
            $key = $temp[0];
            if(isset($temp[1] )) {
                $val = $temp[1];
                $body[$key] =$val;
            }
        }

        $footer = array();
        foreach( explode(';', $mailTemplate_style->style_footer) as $footer_style) {
            $temp = explode(':',$footer_style);
            $key = $temp[0];
            if(isset($temp[1] )) {
                $val = $temp[1];
                $footer[$key] =$val;
            }
        }
        $mailTemplate_text = $mailTemplate->mailText;

        $header_text = array();
        foreach( explode(';', $mailTemplate_text->text_header) as $text_header) {
            $temp = explode(':',$text_header);
            $key = $temp[0];
            if(isset($temp[1] )) {
                $val = $temp[1];
                $header_text[$key] = $val;
            }
        }
      /*   dd(explode(';', $mailTemplate_text->text_header)); */
      /*   dd(  $header_text ); */
        $body_text = array();
        $footer_text = array();

        return view('Centaur::mail_templates.edit', ['mailTemplate' => $mailTemplate,'elements' => $elements, 'docs' => $docs, 'mailTemplate_style' => $mailTemplate_style, 'header' => $header, 'body' => $body, 'footer' => $footer, 'header_text' => $header_text ]);

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

        $data = array(
			'name'          => $request['name'],
			'description'   => $request['description'],
			'for_mail'      => $request['for_mail'],
		);
		
        $mailTemplate->updateMailTemplate($data);
       
        $elements = array('header', 'body','footer');
        $style = array();
        foreach( $elements as $element ) {
            $style_text = '';
            foreach(  $request[ $element ] as $key => $value ) {
                $style_text .= ($key . ':' . $value.';');
            }
            $style[ $element ] = $style_text;
        }
       
        $data_style = array(
            'mail_id'       => $mailTemplate->id,
            'style_header'  => $style['header'],
            'style_body'    => $style['body'],
            'style_footer'  => $style['footer'],
        );

        $mailStyle->updateMailStyle($data_style);

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
