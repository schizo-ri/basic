<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Designing;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\DesigningMail;

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
    public function index()
    {
        $designings = Designing::orderBy('project_no','ASC')->get();
        $users = User::get();

        return view('Centaur::designings.index',['designings' => $designings,'users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::get();
        
        return view('Centaur::designings.create', ['users' => $users]);
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

        $email = $designing->designer->email;

        try {
            Mail::to($email)->send(new DesigningMail($designing )); 
            $message .= "Poruka projektantu je poslana ";
        } catch (\Throwable $th) {
            $message .= "Došlo je do problema, poruka nije poslana. ";
            $type_message = 'error';
        }

        if ($request->hasFile('fileToUpload')) { 
            $file = $request->file('fileToUpload');

            $path = 'uploads/';
            if (! file_exists($path)) {
                mkdir($path);
            }
            $path =  $path . $designing->id . '/';
            if (! file_exists($path)) {
                mkdir($path);
            }
            $docName = $request->file('fileToUpload')->getClientOriginalName();  //file name
            $docSize =  $request->file('fileToUpload')->getClientSize();         //file size 
            $target_file = $path . $docName;
            $docType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));    //file extension

            if (file_exists($target_file)) {                                       // Check if file already exists
                $message .= "Dokument s tim nazivom već postoji! ";
                $type_message = 'error';
                return redirect()->back()->with($type_message,  $message);  
              }
             
              if ($docSize > 5000000) {                                             // Check file size
                $message .= "Dokument je prevelik, dopušteno 5000000 kb.Tvoj dokument ima " . $docSize . " kb. ";
                $type_message = 'error';
                return redirect()->back()->with($type_message,  $message);  
              }
              
              if($docType == "exe" || $docType == "bin") {                         // Allow certain file formats
                $message .= "Nedozvoljeni tip dokumenta! ";
                $type_message = 'error';
                return redirect()->back()->with($type_message,  $message);  
              }

              try {
                $request->file('fileToUpload')->move($path, $docName);
                $message .= "Dokumenat je spremljen! ";
                
              } catch (\Throwable $th) {
                $message .= "Došlo je do problema, dokumenat nije spremljen. ";
                $type_message = 'error';
              }
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
        $designing = Designing::find($id);
        $users = User::get();
        $docs = array();

        
      
        return view('Centaur::designings.edit', ['designing' => $designing, 'users' => $users]);
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
        if(isset($request['designing_id'])) {
            $data = array(
                'designer_id' => $request['designer_id'],
            );
    
            $designing->updateDesigning($data);
           
            $message .= 'Podaci su spremljeni. ';
            $type_message = 'success';
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

            if ($request->hasFile('fileToUpload')) { 
                $file = $request->file('fileToUpload');

                $path = 'uploads/';
                if (! file_exists($path)) {
                    mkdir($path);
                }
                $path =  $path . $designing->id . '/';
                if (! file_exists($path)) {
                    mkdir($path);
                }
                $docName = $request->file('fileToUpload')->getClientOriginalName();  //file name
                $docSize =  $request->file('fileToUpload')->getClientSize();         //file size 
                $target_file = $path . $docName;
                $docType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));    //file extension

                if (file_exists($target_file)) {                                       // Check if file already exists
                    $message .= "Dokument s tim nazivom već postoji! ";
                    $type_message = 'error';
                    return redirect()->back()->with($type_message,  $message);  
                }
                
                if ($docSize > 5000000) {                                             // Check file size
                    $message .= "Dokument je prevelik, dopušteno 5000000 kb.Tvoj dokument ima " . $docSize . " kb. ";
                    $type_message = 'error';
                    return redirect()->back()->with($type_message,  $message);  
                }
                
                if($docType == "exe" || $docType == "bin") {                         // Allow certain file formats
                    $message .= "Nedozvoljeni tip dokumenta! ";
                    $type_message = 'error';
                    return redirect()->back()->with($type_message,  $message);  
                }

                try {
                    $request->file('fileToUpload')->move($path, $docName);
                    $message .= "Dokumenat je spremljen! ";
                    
                } catch (\Throwable $th) {
                    $message .= "Došlo je do problema, dokumenat nije spremljen. ";
                    $type_message = 'error';
                }
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
}
