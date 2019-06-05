<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AdRequest;
use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdCategory;
use App\Models\Employee;
use Sentinel;

class AdController extends Controller
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
    public function index(Request $request)
    {
       
		if(isset($request->category_id)) {
			$category = AdCategory::where('id',$request->category_id)->first();
			$ads = Ad::where('category_id',$category->id )->get();

			return view('Centaur::ads.index', ['ads' => $ads, 'category' => $category]);
		} else {
			$ads = Ad::get();
			return view('Centaur::ads.index', ['ads' => $ads]);
		}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$categories = AdCategory::get();
		
		if(isset($request->category_id)) {
			return view('Centaur::ads.create',['categories' => $categories, 'category_id' => $request->category_id]);
		} else {
			return view('Centaur::ads.create',['categories' => $categories]);
		}

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdRequest $request)
    {
        $user = Sentinel::getUser();
		$employee = Employee::where('user_id', $user->id)->first();
		
		$data = array(
			'employee_id'  	=> $employee->id,
			'category_id'  	=> $request['category_id'],
			'subject'  		=> $request['subject'],
			'description'  	=> $request['description'],
			'price'  		=> $request['price'],
		);
			
		$ad = new Ad();
		$ad->saveAd($data);
		
		session()->flash('success', "Podaci su spremljeni");
		
        return redirect()->route('ads.index', ['category_id' => $request['category_id']]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$ad = Ad::find($id);
		
		return view('Centaur::ads.show',['ad' => $ad ]);
    }
	
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ad = Ad::find($id);
		$categories = AdCategory::get();
		
		return view('Centaur::ads.edit',['ad' => $ad,'categories' => $categories ]);
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
        $ad = Ad::find($id);
		
		$user = Sentinel::getUser();
		$employee = Employee::where('user_id', $user->id)->first();
		
		$data = array(
			'employee_id'  	=> $employee->id,
			'category_id'  	=> $request['category_id'],
			'subject'  		=> $request['subject'],
			'description'  	=> $request['description'],
			'price'  		=> $request['price'],
		);
		
		$ad->updateAd($data);
		
		session()->flash('success', "Podaci su ispravjeni");
		
        return redirect()->route('ads.index', ['category_id' => $request['category_id']]);	
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ad = Ad::find($id);
		$ad->delete();
		
		$message = session()->flash('success', 'Oglas je obrisan.');
		
		return redirect()->back()->withFlashMessage($message);
    }
	
	/**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function oglasnik()
    {
		$ads = Ad::orderBy('created_at','DESC')->get();

		return view('Centaur::oglasnik',['ads'=> $ads]);
    }
}
