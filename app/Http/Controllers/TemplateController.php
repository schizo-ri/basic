<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Template;
use Sentinel;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates = Template::get();
        $empl = Sentinel::getUser()->employee;
        $permission_dep = array();
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        } 
        
        return view('Centaur::templates.index', ['templates' => $templates,'permission_dep' => $permission_dep] );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $templates = Template::get();
        
        return view('Centaur::templates.create', ['templates' => $templates] );
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
            'title'  		=> $request['title'],
            'module'  		=> $request['module'],
            'text'  		=> $request['text'],
            'text_json'  	=> $request['text_json']
        );
       
        $template = new Template();
        $template->saveTemplate($data);
        
        return "ok";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $template = Template::find($id);
        
        return view('Centaur::templates.show', ['template' => $template] );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $template = Template::find($id);
        $templates = Template::get();
        return view('Centaur::templates.edit', ['template' => $template, 'templates' => $templates] );
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
        $template = Template::find($id);

        $data = array(
            'title'  		=> $request['title'],
            'module'  		=> $request['module'],
            'text'  		=> $request['text'],
            'text_json'  	=> $request['text_json']
        );
       
        $template->updateTemplate($data);
        
        return "ok";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $template = Template::find($id);
        $template->delete();

        $message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
}
