<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Designing;
use App\Models\PreparationEmployee;
use App\Models\Preparation;
use App\Models\Project;
use App\User;
use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Support\Facades\Mail;
use App\Mail\DesigningMail;
use App\Mail\NewDesigningMail;
use App\Mail\DesigningReminderMail;
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
        })->orderBy('first_name','ASC')->with('designins')->with('hasDesigningEmployees')->get();

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

        return view('Centaur::designings.index',['all_designings' => $designings,'designers' => $designers,'users' => $users,'active' => $active]);
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
            'cabinet_name'=> $request['cabinet_name'],
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
              
              //  $email = 'jelena.juras@duplico.hr';
                Mail::to($email)->send(new NewDesigningMail( $designing )); 
            }
            $message .= "Poruka administratoru je poslana ";
        } catch (\Throwable $th) {
            $message .= "Došlo je do problema, poruka nije poslana. ";
            $type_message = 'error';
        }
      
        if( $designing->designer ) {
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
       
        /* if(isset($request['designing_id'])) {
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
        } else */
        if ($request['file_up']) {
            $files_troskovnik = $request->file('troskovnikToUpload');
            $tip = 'Troskovnik';
            if( $files_troskovnik ) {
                
                if(count($files_troskovnik)> 0) {
                    foreach ($files_troskovnik as $key => $file) {
                        $file_name = $request['file_name'][$key];
                        $resp = $this->file_upload( $file, $file_name, $designing->id,  $tip );
                    }
                }
             
                $message .= $resp['message'];
                $type_message = $resp['type_message'];
            } else {
                $message .= "Nisi odabrao " . $tip.' ';
                $type_message = 'error';
            }
            $files_shema = $request->file('shemaToUpload');
            $tip = 'Shema';
            if( $files_shema ) {
                
                if(count($files_shema)> 0) {
                    foreach ($files_shema as $key => $file) {
                        $file_name = $request['file_name'][$key];
                        $resp = $this->file_upload( $file, $file_name, $designing->id, $tip );
                    }
                }
             
                $message .= $resp['message'];
                $type_message = $resp['type_message'];
            } else {
                $message .= "Nisi odabrao " . $tip.' ';
                $type_message = 'error';
            }
            $files_project = $request->file('projectToUpload');
            $tip = 'Projekt';
            if( $files_project ) {
               
                if(count($files_project)> 0) {
                    foreach ($files_project as $key => $file) {
                        $file_name = $request['file_name'][$key];
                        $resp = $this->file_upload( $file, $file_name, $designing->id, $tip );
                    }
                }
             
                $message .= $resp['message'];
                $type_message = $resp['type_message'];
            } else {
                $message .= "Nisi odabrao " . $tip .' ';
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
            
              /*   if($email) {
                    try {
                        Mail::to($email)->send(new DesigningAssignedDesignerMail( $designing )); 
                        $message .= "Poruka voditelju je poslana ";
                    } catch (\Throwable $th) {
                        $message .= "Došlo je do problema, poruka nije poslana. ";
                        $type_message = 'error';
                    }
                } */
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

        if( count($designing->hasEmployees ) > 0) {
            foreach ($designing->hasEmployees as $designingEmployees) {
                $designingEmployees->delete();
            }
        }
        if ( $designing ) {
            $designing->delete();
        }
      
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

    public function file_upload($file, $file_name, $id, $tip) 
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

        $path =  $path . $tip . '/';
        if (! file_exists($path)) {
            mkdir($path);
        }

        $old_files = glob($path.'*');
        if( count($old_files) > 0) {
            foreach($old_files as $old_file) {
                if(is_file($old_file)) {
                    unlink($old_file); 
                }
            }
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
            $message .=  $tip . " s tim nazivom već postoji! ";
            $type_message = 'error';
            return array( 'message' => $message, 'type_message' => $type_message );
        }
        
      /*   if ($docSize > 10000000) {                                             // Check file size
            $message .= $tip . " je prevelik, dopušteno 10000000 kb.Tvoj dokument ima " . $docSize . " kb. ";
            $type_message = 'error';
            return array( 'message' => $message, 'type_message' => $type_message );
        } */
        
        if($docType == "exe" || $docType == "bin") {                         // Allow certain file formats
            $message .= "Nedozvoljeni tip dokumenta " .$tip .'!';
            $type_message = 'error';
            return array( 'message' => $message, 'type_message' => $type_message );
        }

        try {
            $file->move($path, $file_name);
            $message .= 'Dokument '.$tip . " je spremljen! ";
            
        } catch (\Throwable $th) {
            $message .= "Došlo je do problema, dokument '.$tip.' nije spremljen. ";
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

    public function toProduction ($id) 
    {
        $designing = Designing::find($id);

        $data = array(
            'finished' => 1,
        );
        
        $designing->updateDesigning($data);

        $data = array(
            'name'                  => $designing->cabinet_name,
            'project_name'          => $designing->name,
            'project_no'            => $designing->project_no,
            'project_manager'       => $designing->manager_id,
            'designed_by'           => $designing->designer_id,
            'delivery'              => $designing->date,
            'preparation'           => '{"Oprema preuzeta iz skladišta":"NE","Postavljene oznake na opremu":"NE","Oprema namontirana na ploču ormara":"NE","Periferna oprema postavljena (lampa, grijač, uvodnice...)":"NE","Postavljene i označene stezaljke":"NE"}',
            'mechanical_processing' => '{"Obrađena ploča":"NE","Obrađen ormar":"NE","Postavljeni PF-ovi":"NE","Montirane kanalice":"NE","Montirane din šine":"NE","Postavljena oprema na vrata ormara":"NE"}',
            'marks_documentation'   => '{"Pripremljene oznake za opremu":"NE","Pripremljene oznake za stezaljke":"NE","Pripremljene i postavljene oznake s nazivom ormara i QR pločica":"NE","Pripremljene opisne oznake":"NE","Napravljena izlazna dokumentacija i pripremljena shema":"NE"}',
        );
      
        $preparation = new Preparation();
        $preparation->savePreparation($data);

        $data = array(
            'name'           => $designing->name,
            'project_no'     => $designing->project_no,
            'preparation_id' => $preparation->id,
            'start_date'     => date('Y-m-d',strtotime($preparation->created_at)),
            'end_date'       => $preparation->delivery,
        );

        $project = new Project();
        $project->saveProject($data);

        session()->flash( 'success', 'Projekt je prebačen u proizvodnju');
		
        return redirect()->back();
    }

    public function reminder ($id) 
    {
        $designing = Designing::find($id);
        
        $email = $designing->manager->email;
      /*   $email = 'jelena.juras@duplico.hr'; */
       
        $doc_troskovnik = array();
        $doc_shema = array();
        $doc_project = array();
        $path = 'uploads/' . $designing->id . '/Projekt/';
        if(file_exists($path)){
            $doc_project = array_diff(scandir($path), array('..', '.', '.gitignore'));
        } 
        $path1 = 'uploads/' . $designing->id . '/Shema/';
        if(file_exists($path1)){
            $doc_shema = array_diff(scandir($path1), array('..', '.', '.gitignore'));
        } 
        $path2 = 'uploads/' . $designing->id . '/Troskovnik/';
        if(file_exists($path2)){
            $doc_troskovnik = array_diff(scandir($path2), array('..', '.', '.gitignore'));
        } 
        $missing_doc = array();
        if(  count( $doc_project) == 0 ) {
            array_push($missing_doc, 'Glavni projekt');
        }
        if(  count( $doc_shema) == 0 ) {
            array_push($missing_doc, 'Jednopolna shema');
        }
        if(  count( $doc_troskovnik) == 0 ) {
            array_push($missing_doc, 'Troškovnik');
        }
        
        if( count( $missing_doc ) > 0 ) {
            Mail::to($email)->send(new DesigningReminderMail( $designing, $missing_doc )); 
            session()->flash( 'success', 'Poruka je poslana');
		
            return redirect()->back();
        } else {
            session()->flash( 'success', 'Poruka nije poslana, sva dokumentacija je priložena');
		
            return redirect()->back();
        }
    }
}
