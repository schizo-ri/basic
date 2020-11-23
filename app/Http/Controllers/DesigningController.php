<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Designing;
use App\Models\PreparationEmployee;
use App\User;
use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Support\Facades\Mail;
use App\Mail\DesigningMail;
use App\Mail\NewDesigningMail;
use App\Mail\DesigningAssignedDesignerMail;
use DateInterval;
use DateTime;
use DatePeriod;

class DesigningController extends Controller
{
    public function __construct()
    {
        // Middleware
        $this->middleware('sentinel.auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index ( Request $request )
    {
        if( isset($request['active'])) {
            $active = $request['active'];
        } else {
            $active = 1;
        }
        $designings = Designing::orderBy('created_at','ASC')->where('active', $active )->get();
     /*    $preparationEmployee = PreparationEmployee::get(); */
    
        
        $designers = array();
        foreach ($designings->unique('designer_id') as $designer) {
           array_push( $designers, $designer->designer);
        }
       
        $users = EloquentUser::whereHas('roles', function ($query) {
            return $query->where('slug', 'projektant');
        })->orderBy('first_name','ASC')->with('designins')->get();

        $today = new Datetime(date('Y-m-d 00:00'));

        foreach ($users as $user) {
            $designing_user = $user->designins;
            foreach ($designing_user as $desining) {
                $array_dates = array();
                
                $begin = new DateTime( $desining->start );
                $end = new DateTime( $desining->end );
                $end->setTime(0,0,1);
                $interval = new DateInterval('P1D');
                $daterange = new DatePeriod($begin, $interval,$end);
                $colspan = 0;
                foreach ($daterange as $day ) {
                    if( $day->format('N') <= 5 ) {
                        array_push($array_dates,$day->format('Y-m-d') );
                        $colspan++;
                    }
                }
                $desining->list_date = $array_dates;
                $desining->colspan = $colspan;
                if( $today > $begin ) {
                    $desining->first_date = $today->format('Y-m-d');
                } else {
                    $desining->first_date = $begin->format('Y-m-d');
                }
            }
        }

        return view('Centaur::designings.index',['designings' => $designings,'designers' => $designers,'users' => $users,'active' => $active]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projektanti = EloquentUser::whereHas('roles', function ($query) {
            return $query->where('slug', 'projektant');
        })->orderBy('first_name','ASC')->with('designins')->get();
        $voditelji = EloquentUser::whereHas('roles', function ($query) {
            return $query->where('slug', 'voditelj');
        })->orderBy('first_name','ASC')->with('designins')->get();

        return view('Centaur::designings.create', ['projektanti' => $projektanti,'voditelji' => $voditelji]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = '';
       
        $data = array(
            'project_no'  => trim($request['project_no']),
            'name'        => $request['name'],
            'date'        => $request['date'],
            'manager_id'  => $request['manager_id'],
            'designer_id' => $request['designer_id'],
            'comment'     => $request['comment'],
        );
        
        $designing = new Designing();
        $designing->saveDesigning($data);
        
        $message .= 'Podaci su spremljeni. ';
        $type_message = 'success';

        try {
            $administrators = EloquentUser::whereHas('roles', function ($query) {
                return $query->where('slug', 'administrator');
            })->orderBy('first_name','ASC')->with('designins')->get();
            
            foreach ($administrators as $administrator) {
                $email = $administrator->email;
              
              /*   $email = 'jelena.juras@duplico.hr'; */
                Mail::to($email)->send(new NewDesigningMail( $designing )); 
            }
            $message .= "Poruka administratoru je poslana ";
        } catch (\Throwable $th) {
            $message .= "Došlo je do problema, poruka nije poslana. ";
            $type_message = 'error';
        }
      
        if($designing->designer ) {
            $email = $designing->designer->email;

            try {
                Mail::to($email)->send(new DesigningMail( $designing )); 
                $message .= "Poruka projektantu je poslana ";
            } catch (\Throwable $th) {
                $message .= "Došlo je do problema, poruka nije poslana. ";
                $type_message = 'error';
            }
        }
       
        if ($request->hasFile('fileToUpload')) { 
            $file = $request->file('fileToUpload');
            $file_name = $request['file_name'];
            $response = $this->file_upload( $file, $file_name, $designing->id );
            
            $message .= $response['message'];
            $type_message = $response['type_message'];
        }

        session()->flash($type_message, $message);
            
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
        $designing = Designing::find($id);

        return view('Centaur::designings.show', ['designing' => $designing]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $designing = Designing::find($id);
        $projektanti = EloquentUser::whereHas('roles', function ($query) {
            return $query->where('slug', 'projektant');
        })->orderBy('first_name','ASC')->with('designins')->get();
        $voditelji = EloquentUser::whereHas('roles', function ($query) {
            return $query->where('slug', 'voditelj');
        })->orderBy('first_name','ASC')->with('designins')->get();
        $docs = array();
      
        return view('Centaur::designings.edit', ['designing' => $designing,'projektanti' => $projektanti,'voditelji' => $voditelji]);
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
        $designing = Designing::find($id);
        $message = '';
        $type_message = 'success';
       
        if(isset($request['designing_id'])) {
            $data = array(
                'designer_id' => $request['designer_id'],
                'start' => $request['start'],
                'end' => $request['end'],
            );
            
            $designing->updateDesigning($data);
       
            $message .= 'Podaci su spremljeni. ';
            $type_message = 'success';
            $email = $designing->manager ? $designing->manager->email : null;
            
            if($email) {
                try {
                    Mail::to($email)->send(new DesigningAssignedDesignerMail( $designing )); 
                    $message .= "Poruka voditelju je poslana ";
                } catch (\Throwable $th) {
                    $message .= "Došlo je do problema, poruka nije poslana. ";
                    $type_message = 'error';
                }
            }
        } elseif ($request['file_up']) {
            $files = $request->file('fileToUpload');
            if( $files ) {
                if(count($files)> 0) {
                    foreach ($files as $key => $file) {
                        $file_name = $request['file_name'][$key];
                        $resp = $this->file_upload( $file, $file_name, $designing->id );
                    }
                }
             
                $message .= $resp['message'];
                $type_message = $resp['type_message'];
            } else {
                $message .= "Nisi odabrao dokumenat!";
                $type_message = 'error';
            }
        } else {
            $data = array(
                'project_no'  => trim($request['project_no']),
                'name'        => $request['name'],
                'date'        => $request['date'],
                'manager_id'  => $request['manager_id'],
                'designer_id' => $request['designer_id'],
                'comment'     => $request['comment'],
            );
           
            $designing->updateDesigning($data);
            $message .= 'Podaci su spremljeni. ';
            $type_message = 'success';

            if( $designing->designer_id != $request['designer_id']) {
                $email = $designing->manager ? $designing->manager->email : null;
            
                if($email) {
                    try {
                        Mail::to($email)->send(new DesigningAssignedDesignerMail( $designing )); 
                        $message .= "Poruka voditelju je poslana ";
                    } catch (\Throwable $th) {
                        $message .= "Došlo je do problema, poruka nije poslana. ";
                        $type_message = 'error';
                    }
                }
            }

            if ($request->hasFile('fileToUpload')) { 
                $files = $request->file('fileToUpload');
                if(count($files)> 0) {
                    foreach ($files as $key => $file) {
                        $file_name = $request['file_name'][$key];
                        $response = $this->file_upload( $file,$file_name, $designing->id );
                    }
                }
               
                $message .= $response['message'];
                $type_message = $response['type_message'];
            }
        }
        
        session()->flash( $type_message,  $message);
		
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $designing = Designing::find($id);
        $designing->delete();

        $message = session()->flash('success', "Projekt je obrisan");                    
        return redirect()->back()->withFlashMessage($message);
    }

    public function delete_file(Request $request) 
    {
        $path = $request['file'];
    
        if (file_exists($path)) {
            unlink($path);
        }
    
        $message = session()->flash('success',  "Dokumenat je obrisan");
          
        return redirect()->back()->withFlashMessage($message);
    }

    public function file_upload($file, $file_name, $id) 
    {
        $message = '';
        $type_message = 'success';
        $path = 'uploads/';
        if (! file_exists($path)) {
            mkdir($path);
        }
        $path =  $path . $id . '/';
        if (! file_exists($path)) {
            mkdir($path);
        }
        $docName = $file->getClientOriginalName();  // original file name
        $docSize =  $file->getClientSize();         //file size 
        $target_file = $path . $docName;
       
        $docType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));    //file extension
        if($file_name == '') {
            $file_name = $docName;
        } else {
            $file_name = $file_name.'.'.$docType;
        }
        if (file_exists($target_file)) {                                       // Check if file already exists
            $message .= "Dokument s tim nazivom već postoji! ";
            $type_message = 'error';
            return array( 'message' => $message, 'type_message' => $type_message );
        }
        
        if ($docSize > 10000000) {                                             // Check file size
            $message .= "Dokument je prevelik, dopušteno 10000000 kb.Tvoj dokument ima " . $docSize . " kb. ";
            $type_message = 'error';
            return array( 'message' => $message, 'type_message' => $type_message );
        }
        
        if($docType == "exe" || $docType == "bin") {                         // Allow certain file formats
            $message .= "Nedozvoljeni tip dokumenta! ";
            $type_message = 'error';
            return array( 'message' => $message, 'type_message' => $type_message );
        }

        try {
            $file->move($path, $file_name);
            $message .= "Dokumenat je spremljen! ";
            
        } catch (\Throwable $th) {
            $message .= "Došlo je do problema, dokumenat nije spremljen. ";
            $type_message = 'error';
        }

        return array( 'message' => $message, 'type_message' => $type_message );
    }

    public function close_designing ($id) 
    {
        $designing = Designing::find($id);

        if ($designing->active == 1) {
            $active = 0;
        } else {
            $active = 1;
        }

        $data = array(
			'active' => $active		
        );

        $designing->updateDesigning($data);

        if ($designing->active == 0) {
            session()->flash('success', "Podaci su spremljeni, projekt je neaktivan.");
        } else {
            session()->flash('success', "Podaci su spremljeni, projekt je aktivan.");
        }
        
        return redirect()->back();
    }

}
