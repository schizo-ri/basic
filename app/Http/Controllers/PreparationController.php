<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Preparation;
use App\Models\EquipmentList;
use App\Models\Designing;
use App\User;
use Cartalyst\Sentinel\Users\EloquentUser;
use App\Models\PreparationRecord;
use App\Models\ListUpdate;
use App\Imports\EquipmentImport;
use App\Imports\EquipmentImportSiemens;
use Maatwebsite\Excel\Facades\Excel;
use Sentinel;
use DB;
use Cartalyst\Sentinel\Roles\EloquentRole;
use Illuminate\Support\Facades\Mail;
use App\Mail\PreparationFinishMail;
use App\Mail\ErrorMail;

class PreparationController extends Controller
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
    public function index( Request $request )
    {
        if( isset($request['active'])) {
            $active = $request['active'];
        } else {
            $active = 1;
        }

        $preparations = Preparation::where('active', $active)->orderBy('created_at','ASC')->with('employees')->get()->groupBy('project_no');
       
       /*  $users = EloquentUser::orderBy('first_name','ASC')->with('roles')->get(); */
        $users = EloquentUser::whereHas('roles', function ($query) {
            return $query->where('slug', 'priprema');
        })->orderBy('first_name','ASC')->get();
        /* $all_users = User::get(); */

        $projektanti = EloquentUser::whereHas('roles', function ($query) {
            return $query->where('slug', 'projektant');
        })->orderBy('first_name','ASC')->with('designins')->get();

        $voditelji = EloquentUser::whereHas('roles', function ($query) {
            return $query->where('slug', 'voditelj');
        })->orderBy('first_name','ASC')->with('designins')->get();

        return view('Centaur::preparations.index', ['users' => $users,'preparations' => $preparations,'projektanti' => $projektanti,'voditelji' => $voditelji,'active' => $active]);

    }

    public function preparations_active (Request $request)
    {
        if(isset($request['active'])) {
            $active = $request['active'];
        } else {
            $active = 1;
        }

        $preparations = Preparation::where('active',$active)->orderBy('project_no','ASC')->get()->groupBy('project_no');

        $users = EloquentUser::whereHas('roles', function ($query) {
            return $query->where('slug', 'priprema');
        })->orderBy('first_name','ASC')->get();
        $projektanti = EloquentUser::whereHas('roles', function ($query) {
            return $query->where('slug', 'projektant');
        })->orderBy('first_name','ASC')->with('designins')->get();

        $voditelji = EloquentUser::whereHas('roles', function ($query) {
            return $query->where('slug', 'voditelj');
        })->orderBy('first_name','ASC')->with('designins')->get();
        return view('Centaur::preparations.index', ['users' => $users,'preparations' => $preparations,'projektanti' => $projektanti,'voditelji' => $voditelji]);

        /*  $mehanicka = collect(['Obrađena ploča','Obrađen ormar','Postavljeni PF-ovi','Montirane kanalice','Montirane din šine','Postavljena oprema na vrata ormara']);
        $oznake = collect(['Pripremljene oznake za opremu','Pripremljene oznake za stezaljke','Pripremljene i postavljene oznake s nazivom ormara i QR pločica','Pripremljene opisne oznake','Napravljena izlazna dokumentacija i pripremljena shema']);
        $priprema = collect(['Oprema preuzeta iz skladišta','Postavljene oznake na opremu','Oprema namontirana na ploču ormara','Periferna oprema postavljena (lampa, grijač, uvodnice...)','Postavljene i označene stezaljke']); */
       
       /* 
        $roles = Sentinel::getUser()->roles->toArray();
        $roles_text = '';
        foreach ($roles as $role) {
            $roles_text .=  $role['slug'] . ',';
        }
        */
        /* return view('Centaur::preparations.index', ['users' => $users,'preparations' => $preparations,'priprema' => $priprema, 'mehanicka' => $mehanicka,'oznake' => $oznake, 'roles' => substr($roles_text, 0, -1)]); */
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if( isset($request['designing_id']) ) {
            $designing = Designing::find($request['designing_id']);
            if( $designing ) {
                return view('Centaur::preparations.create',['designing_id' => $request['designing_id']] );
            }
        } 
        return "Nemoguće izvršiti";
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( isset($request['designing_id']) ) {
            $designing = Designing::find($request['designing_id']);
        } else {
            $designing = null;
        }

        if( $designing ) {
            $data = array(
                'finished' => 1,
            );
            
            $designing->updateDesigning($data);
        }

        $data = array(
            'name'                  => $designing ? $designing->cabinet_name : $request['name'],
            'project_name'          => $designing ? $designing->name : $request['project_name'],
            'project_no'            => $designing ? $designing->project_no : str_replace(" ", "_",$request['project_no']),
            'project_manager'       => $designing ? $designing->manager_id : $request['project_manager'],
            'designed_by'           => $designing ? $designing->designer_id : $request['designed_by'],
            'preparation'           => '{"Oprema preuzeta iz skladišta":"NE","Postavljene oznake na opremu":"NE","Oprema namontirana na ploču ormara":"NE","Periferna oprema postavljena (lampa, grijač, uvodnice...)":"NE","Postavljene i označene stezaljke":"NE"}',
            'mechanical_processing' => '{"Obrađena ploča":"NE","Obrađen ormar":"NE","Postavljeni PF-ovi":"NE","Montirane kanalice":"NE","Montirane din šine":"NE","Postavljena oprema na vrata ormara":"NE"}',
            'marks_documentation'   => '{"Pripremljene oznake za opremu":"NE","Pripremljene oznake za stezaljke":"NE","Pripremljene i postavljene oznake s nazivom ormara i QR pločica":"NE","Pripremljene opisne oznake":"NE","Napravljena izlazna dokumentacija i pripremljena shema":"NE"}',
            'delivery'              => $request['delivery'],
        );
      
        $preparation = new Preparation();
        $preparation->savePreparation($data);

        $data = array(
            'name'           => $designing ? $designing->name : $request['name'],
            'project_no'     => $designing ? $designing->project_no : $request['project_no'],
            'preparation_id' => $preparation->id,
            'start_date'     => date('Y-m-d',strtotime($preparation->created_at)),
            'end_date'       => $request['delivery'],
        );

        $project = new Project();
        $project->saveProject($data);
        
        if(request()->file('file')) {
            if( $request['siemens'] == "1" ) {
                Excel::import(new EquipmentImportSiemens, request()->file('file')); 
            } else {
                Excel::import(new EquipmentImport, request()->file('file'));
            }
        }
       

        session()->flash('success', "Podaci su spremljeni");
        
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $preparation = Preparation::find($id);
        if( $preparation ) {
            $active = $preparation->active;
            if($preparation) {
                $preparations = Preparation::where('project_no', $preparation->project_no )->where('active', $preparation->active)->orderBy('project_no','ASC')->get();
            } else {
                $preparations = collect();
            }
           
            $roles = Sentinel::getUser()->roles->toArray();
            $roles_text = '';
            foreach ($roles as $role) {
                $roles_text .=  $role['slug'] . ',';
            }
            $mehanicka = collect(['Obrađena ploča','Obrađen ormar','Postavljeni PF-ovi','Montirane kanalice','Montirane din šine','Postavljena oprema na vrata ormara']);
            $oznake = collect(['Pripremljene oznake za opremu','Pripremljene oznake za stezaljke','Pripremljene i postavljene oznake s nazivom ormara i QR pločica','Pripremljene opisne oznake','Napravljena izlazna dokumentacija i pripremljena shema']);
            $priprema = collect(['Oprema preuzeta iz skladišta','Postavljene oznake na opremu','Oprema namontirana na ploču ormara','Periferna oprema postavljena (lampa, grijač, uvodnice...)','Postavljene i označene stezaljke']);
    
            $users = User::orderBy('first_name','ASC')->get(); 
            $projektanti = EloquentUser::whereHas('roles', function ($query) {
                return $query->where('slug', 'projektant');
            })->orderBy('first_name','ASC')->with('designins')->get();
    
            $voditelji = EloquentUser::whereHas('roles', function ($query) {
                return $query->where('slug', 'voditelj');
            })->orderBy('first_name','ASC')->with('designins')->get();

            return view('Centaur::preparations.show', ['preparations' => $preparations,'projektanti' => $projektanti,'voditelji' => $voditelji, 'users' => $users,'roles' => substr($roles_text, 0, -1), 'priprema' => $priprema, 'mehanicka' => $mehanicka,'oznake' => $oznake,]);
        } else {
            return redirect()->route('preparations.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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
        $preparation = Preparation::find($id);
        
        $preparation_val = array();
        $marks_val = array();
        $mehan_val = array();
       
        $data = array(
            'name' => $request['name'],
            'project_name'   => $request['project_name'],
            'project_no'  => str_replace(" ", "_",$request['project_no']),
            'project_manager'  => $request['project_manager'],
            'designed_by'  => $request['designed_by'],
            'delivery'  => $request['delivery'],
        );
        if(isset($request['preparation_title'])) {
            foreach ($request['preparation_title'] as $key_title => $title) {
                foreach ($request['preparation'] as $key_value => $value) {
                    if($key_title == $key_value) {
                        $preparation_val += [$key_title => $value];
                    }
                }
            }
            $data += ['preparation'  => json_encode($preparation_val )];
        }
        if(isset($request['mechanical_title'])) {
            foreach ($request['mechanical_title'] as $key_title => $title) {
                foreach ($request['mechanical_processing'] as $key_value => $value) {
                    if($key_title == $key_value) {
                        $mehan_val += [$key_title => $value];
                    }
                }
            }
            $data += [ 'mechanical_processing'  => json_encode($mehan_val )];
        }
        if(isset($request['marks_title'])) {
            foreach ($request['marks_title'] as $key_title => $title) {
                foreach ($request['marks_documentation'] as $key_value => $value) {
                    if($key_title == $key_value) {
                        $marks_val += [$key_title =>  $value];
                    }
                }
            }
            $data += ['marks_documentation' => json_encode($marks_val )];
        }

        $preparation->updatePreparation($data);

        /*   
            $today = date('Y-m-d');
            if( $request['preparation'] || $request['mechanical_processing'] || $request['marks_documentation']) {            
                $data = array(
                    'preparation_id' => $preparation->id,
                    'preparation'  => json_encode($preparation_val ),
                    'mechanical_processing'  =>  json_encode($mehan_val ),
                    'marks_documentation'   => json_encode($marks_val ),
                    'date'  => date('Y-m-d'),
                );
                
                $preparationRecord = PreparationRecord::where('preparation_id', $preparation->id)->whereDate('created_at', $today)->first();

                if($preparationRecord ) {
                    $preparationRecord->updatePreparationRecord($data);
                } else {
                    $preparationRecord = new PreparationRecord();
                    $preparationRecord->savePreparationRecord($data);
                }           
            }
        */

        session()->flash('success', "Podaci su ispravljeni");
        
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
        $preparation = Preparation::find($id);
        if($preparation) {
            $preparation->delete();
        }

        $preparationRecords = PreparationRecord::where('preparation_id', $preparation->id)->get();
        foreach ($preparationRecords as $preparationRecord ) {
            $preparationRecord->delete();
        }
        $equipments = EquipmentList::where('preparation_id', $preparation->id)->get();
        foreach ($equipments as $equipment ) {
            $equipment->delete();
        }

        session()->flash('success', "Podaci su obrisani");
        
      /*   return view('Centaur::preparations.show', $preparation->id); */
        return redirect()->back();
    }

    public function close_preparation ( $id ) 
    {
        $preparation = Preparation::find($id);

        if ($preparation->active == 1) {
            $active = 0;
        } else {
            $active = 1;
        }

        $data = array(
			'active' => $active		
        );

        $preparation->updatePreparation($data);

        if ($preparation->active == 0) {
            session()->flash('success', "Podaci su spremljeni, projekt je neaktivan.");
        } else {
            session()->flash('success', "Podaci su spremljeni, projekt je aktivan.");
        }
      
        return redirect()->back(); 
    }

    public function finished ( $id ) 
    {
        $preparation = Preparation::find($id);
        
        if ($preparation->finish == 1) {
            $finish = 0;
        } else {
            $finish = 1;
        }

        $data = array(
			'finish' =>  $finish
        );

        $preparation->updatePreparation($data);

        $send_to = array('jelena.juras@duplico.hr');
        array_push($send_to, $preparation->manager->email);
        array_push($send_to, $preparation->designed->email);
        array_push($send_to, 'mladen.bockor@duplico.hr');
        array_push($send_to, 'sasa.sindik@duplico.hr');
        array_push($send_to, 'borislav.peklic@duplico.hr');
        
        $message = 'Mail je poslan';
        
        try {
            foreach (array_unique($send_to) as $send_to_mail) {
                if($send_to_mail != '') {
                    Mail::to($send_to_mail)->send(new PreparationFinishMail($preparation)); 
                }
            }
        } catch (\Throwable $th) {
            $email = 'jelena.juras@duplico.hr';
            $url = $_SERVER['REQUEST_URI'];
            Mail::to($email)->send(new ErrorMail($th->getMessage(), $url)); 
            
            $message = "Došlo je do problema, mail nije poslan! " . $th->getMessage();
        } 

        if ($preparation->finish == 0) {
            session()->flash('success', "Podaci su spremljeni, ormr nije spreman za isporuku." .  $message);
        } else {
            session()->flash('success',  "Podaci su spremljeni, ormar je spreman za isporuku." . $message);
        }
      
        return redirect()->back(); 
    }

    public static function delivered( $id) 
    {
        $preparation = Preparation::find($id);
        $equipmentLists = EquipmentList::where('preparation_id',$preparation->id )->where('level1', null)->get();
        
        $quantity = 0;
        $quantity_all = 0;
    
        $delivered_percentage = 0;
        $delivered_percentage_avarage = 0;

        foreach ($equipmentLists as $item) {
            $delivered = 0;
            $quantity = str_replace(",",".",$item->quantity);  
            $equipmentUpdates = ListUpdate::where('item_id',$item->id )->get();

            foreach ($equipmentUpdates as $delivery_update) {
                $delivered += $delivery_update->quantity;
            }
            if($quantity != 0) {
                try {
                    $delivered_percentage += $delivered/$quantity*100;
                } catch (\Throwable $th) {
                    $delivered_percentage = 0;
                }
            }
        }
        
        if(count($equipmentLists) >0 ) {
            $delivered_percentage_avarage = $delivered_percentage / count($equipmentLists);
        }
        
        return round($delivered_percentage_avarage,2,PHP_ROUND_HALF_UP);
    }
}
