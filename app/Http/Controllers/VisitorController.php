<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Support\Facades\Mail;
use Sentinel;

class VisitorController extends Controller
{
    private  $allow;

    /**
	*
	* Set middleware to quard controller.
	* @return void
	*/
	public function __construct()
	{
		$this->allow = array('194.36.47.180', '31.45.236.218','127.0.0.1','194.36.47.178'); 
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( Sentinel::check()) {
            $visitors = Visitor::get();

            return view('Centaur::visitors.index',['visitors' => $visitors]);
        } else {
            return view('errors.guest');
        }
        /* if(!in_array($_SERVER['REMOTE_ADDR'],  $this->allow)) {
            return view('errors.guest');
        } */
        
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = array(
            'first_name'    => $request['first_name'],
            'last_name'     => $request['last_name'],
            'email'         => $request['email'],
            'company'       => $request['company'],
            'accept'        => $request['accept'],
            'confirmed'     => $request['confirmed'],
            'card_id'       => $request['card_id'],            
        );
                
        $work = new Visitor();
        $work->saveVisitor($data);

        if($request['lang'] == 'hr') {
            $text = 'Potvrdili ste da ste upoznati s uvjetima zaštite na radu tvrtke Duplico.';
            $text_error = 'Nešto je pošlo krivo prilikom slanja povratne poruke';
            $text_for_mail = 'Upoznati ste sa uvjetima zaštite na radu tvrtke Duplico.';
            $title = 'Dobrodošli ' . ' u Duplico!';
            $lang =  'hr';
        } else if ($request['lang'] == 'en') {
            $text = 'You have confirmed that you are familiar with the Duplico occupational safety conditions.';
            $text_error = 'Something went wrong when sending a return message';
            $text_for_mail = 'You are familiar with the Duplico occupational safety conditions.';
            $title = 'Welcome ' . ' to Duplico';
            $lang =  'en';
        } else if ($request['lang'] == 'de') {
            $text = 'Hiermit bestätigen sie, dass Sie mit den Duplico-Arbeitsschutzbedingungen vertraut sind.';
            $text_error = 'Beim Senden der Rückmeldung ist ein Fehler aufgetreten.';
            $text_for_mail = 'Sie sind mit den Arbeitsschutzbedingungen von Duplico vertraut.';
            $title = 'Willkommen ' . 'in Duplico!';     
            $lang =  'de'; 
        }
        /* 
            $email = $request['email'];

            try {
                Mail::queue(
                    'email.visitors',
                    ['text_for_mail' => $text_for_mail, 'lang' => $lang],
                    function ($message) use ($email, $title) {
                        $message->to($email)
                            ->from('info@duplico.hr')
                            ->subject($title);
                    }
                );
            } catch (\Throwable $th) {            
                $message = session()->flash('error', $text_error);
                return redirect()->back()->withFlashMessage($message);
            }       
        */
        session()->flash('success', $text);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $visitors = Visitor::orderBy('created_at','DESC')->get();

        return view('Centaur::visitors.show',['visitors' => $visitors]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $visitor = Visitor::find($id);

        return view('Centaur::visitors.edit',['visitor' => $visitor]);
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
        $visitor = Visitor::find($id);

        if(isset($request['only_return'])) {
            $data = array(
                'returned'    => $request['returned']
            );
            $visitor->updateVisitor($data);

            $message = session()->flash('success', 'Datum je promijenjen');
            return redirect()->back()->withFlashMessage($message);
        } else {
            $data = array(
                'first_name'    => $request['first_name'],
                'last_name'     => $request['last_name'],
                'email'         => $request['email'],
                'company'       => $request['company'],
                'card_id'       => $request['card_id'],
            );
            if( $request['returned'] != '') {
                $data += ['returned' => $request['returned']];
            } else {
                $data += ['returned' => null];
            }
            $visitor->updateVisitor($data);

            session()->flash('success', __('ctrl.data_edit'));
    
            /* return redirect()->route('Centaur::visitors.show', 0); */
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $visitor = Visitor::find($id);
        $visitor->delete();

        session()->flash('success', 'Posjetitelj je obrisan.');
		
		return redirect()->back();
    }

    
    public function visitors_show_hr($id)
    {
        if( ! in_array($_SERVER['REMOTE_ADDR'],  $this->allow)) {
            return view('errors.guest');
        }
        
        return view('Centaur::visitors.hr');
    }

    public function visitors_show_de($id)
    {
        if(!in_array($_SERVER['REMOTE_ADDR'],  $this->allow)) {
            return view('errors.guest');
        }

        return view('Centaur::visitors.de');
    }

    public function visitors_show_en($id)
    {
        if(!in_array($_SERVER['REMOTE_ADDR'],  $this->allow)) {
            return view('errors.guest');
        }
        
        return view('Centaur::visitors.en');
    }
}
