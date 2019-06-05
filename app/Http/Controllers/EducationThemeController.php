<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ThemeRequest;
use App\Http\Requests\EducationThemeRequest;
use App\Models\EducationTheme;
use App\Models\Education;

class EducationThemeController extends Controller
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
    public function index(Request $request)
    {
		if($request->education_id){
			$education = Education::where('id', $request->education_id)->first();
			$educationThemes = EducationTheme::where('education_id',$request->education_id )->get();
			
			return view('Centaur::education_themes.index', ['education' => $education, 'educationThemes' => $educationThemes]);
		} else {
			$educationThemes = EducationTheme::get();
			
			return view('Centaur::education_themes.index', ['educationThemes' => $educationThemes]);
		}
		
		
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

		$educations = Education::get();
		
		if(isset($request->education_id)) {
			$education1 = $educations->where('id',$request->education_id)->first();
			
			return view('Centaur::education_themes.create',['educations'=>$educations, 'education1'=>$education1]);
		} else {
			return view('Centaur::education_themes.create',['educations'=>$educations]);
		}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ThemeRequest $request)
    {
		$data = array(
			'name'  	 	=> $request['name'],
			'education_id'  => $request['education_id']
		);
			
		$educationTheme = new EducationTheme();
		$educationTheme->saveEducationTheme($data);
		
		session()->flash('success', "Podaci su spremljeni");
		
        return redirect()->route('education_themes.index',['education_id' => $request['education_id']]);
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
        $educationTheme = EducationTheme::find($id);
		$educations = Education::get();
		
		return view('Centaur::education_themes.edit',['educationTheme'=>$educationTheme,'educations'=>$educations]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ThemeRequest $request, $id)
    {
        $educationTheme = EducationTheme::find($id);
		
		$data = array(
			'name'  	 	=> $request['name'],
			'education_id'  => $request['education_id']
		);
		
		$educationTheme->updateEducationTheme($data);
		
		session()->flash('success', "Podaci su ispravljeni");
		
        return redirect()->route('education_themes.index',['education_id' => $request['education_id']]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $educationTheme = EducationTheme::find($id);
		$educationTheme->delete();
		
		$message = session()->flash('success', 'Tema je obrisana.');
		
		return redirect()->back()->withFlashMessage($message);
    }
}
