<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shortcut;
use App\Models\Table;
use Sentinel;
use Log;

class ShortcutController extends Controller
{
    /**
	*
	* Set middleware to quard controller.
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
        //
    }

    public function shortcutExist (Request $request) 
    {
        $employee = Sentinel::getUser()->employee;
       
        if( $employee ) {
            $shortcut = Shortcut::where('employee_id', $employee->id)->where('url', $request['url'])->first();
            if( $shortcut ) {
                $shortcut_id = $shortcut->id;
            } else {
                $shortcut_id = null;
            }
    
        } else {
            $shortcut_id = null;
        }
       
        return $shortcut_id;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $url =  $request['url'];
        $title =  str_replace('/','', $request['title']);

        $table = Table::where('name', $title)->first();
        
        if($table) {
            $title = $table->description;
        } else {
            $title = '';
        }
      
        return view('Centaur::shortcuts.create',['url' => $url, 'title' => $title]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $employee = Sentinel::getUser()->employee;

        if(isset( $request['table'])) {
            $table = Table::find($request['table']);
            if( $table ) {
                $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].'/'.$table->name;
            }
        } else {
            $url = $request['url'];
        }
        if ( $employee ) {
            $data = array(
                'employee_id'  	=> $employee->id,
                'title'   => $request['title'],
                'color'   => $request['color'],
                'url'     =>  $url,
            );
            
            $shortcut = new Shortcut();
            $shortcut->saveShortcut($data);
            
            session()->flash('success',  __('ctrl.data_save'));
            
            return redirect()->back();
        } else {
            $message = session()->flash('error',  __('ctrl.path_not_allow'));

			return redirect()->back()->withFlashMessage($message);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) // employee->id
    {
        $tables = Table::orderBy('description','ASC')->get();
        
        return view('Centaur::shortcuts.show',['tables' => $tables]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shortcut = Shortcut::find($id);

        return view('Centaur::shortcuts.edit',['shortcut' => $shortcut]);
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
        $shortcut = Shortcut::find($id);
        $employee = Sentinel::getUser()->employee;
        
        if ( $employee ) {
            $data = array(
                'employee_id'  	=> $employee->id,
                'title'   => $request['title'],
                'color'   => $request['color'],
                'url'     => $request['url'],
            );
            
            $shortcut->updateShortcut($data);

            session()->flash('success',  __('ctrl.data_edit'));
            
            return redirect()->back();
        } else {
            $message = session()->flash('error',  __('ctrl.path_not_allow'));
            return redirect()->back()->withFlashMessage($message);
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
        $shortcut = Shortcut::find($id);
        $shortcut->delete();

        /* $message = session()->flash('success',  __('ctrl.data_delete'));
		
        return redirect()->back()->withFlashMessage($message); */
        return redirect()->back();

    }
}
