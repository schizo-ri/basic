<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\ClientReqRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DatabaseController;
use App\Models\Client;
use App\Models\ClientRequest;
use App\Models\Module;

class ClientRequestController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client_requests = ClientRequest::get();
		$modules = Module::get();
		
		return view('Centaur::client_requests.index', ['client_requests' => $client_requests, 'modules' => $modules]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $modules = Module::get();
		
		return view('Centaur::client_requests.create',['modules' => $modules]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data_client = array(
			'name'  		=> $request['name'],
			'address'  		=> $request['address'],
			'city'  		=> $request['city'],
			'oib'  			=> $request['oib'],
			'first_name'  	=> $request['first_name'],
			'last_name'  	=> $request['last_name'],
			'email'  		=> trim($request['email']),
			'phone'  		=> $request['phone']
		);
		
		$client = new Client();
		$client->saveClient($data_client);
		
		$modules = implode(",", $request->module);
		
		$data_request = array(
			'client_id'  	=> $client->id,
			'modules'  		=> $modules,
			'db'  	        =>  $request['db'],
			'url'  	        =>  $request['url']
		);

		$client_req = new ClientRequest();
        $client_req->saveClientRequest($data_request);
        
        if($request['db'] && $request['url'] ) {
            DatabaseController::create($request['db'], $request['url'], $client->id);
        }
		
        $message = session()->flash('success', 'Podaci su spremljeni.');
		
		return redirect()->back()->withFlashMessage($message);
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
        $modules = Module::get();
		$client_request = ClientRequest::find($id);
		
		return view('Centaur::client_requests.edit',['modules' => $modules,'client_request' => $client_request]);
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
        $client_request = ClientRequest::find($id);
		
		$client = Client::find($client_request->client_id);
		 
		$data_client = array(
			'name'  		=> $request['name'],
			'address'  		=> $request['address'],
			'city'  		=> $request['city'],
			'oib'  			=> $request['oib'],
			'first_name'  	=> $request['first_name'],
			'last_name'  	=> $request['last_name'],
			'email'  		=> trim($request['email']),
			'phone'  		=> $request['phone']
		);
		
        $client->updateClient($data_client);

		$modules = implode(",",$request->module);
		
		$data_request = array(
			'client_id'  	=> $client->id,
            'modules'  		=> $modules,
            'db'        	=>  $request['db'],
            'url'  	        =>  $request['url']
		);
		
		$client_request->updateClientRequest($data_request);
		
        if($request['db'] && $request['url'] ) {
            DatabaseController::create($request['db'], $request['url'], $client->id);
        }

		session()->flash('success', "Podaci su ispravljeni");
		
        return redirect()->route('client_requests.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client_request = ClientRequest::find($id);
		$client_request->delete();
		
		$message = session()->flash('success', 'Zahtjev je obrisan.');
		
		return redirect()->back()->withFlashMessage($message);
    }
}
