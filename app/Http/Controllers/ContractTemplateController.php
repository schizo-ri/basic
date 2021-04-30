<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ContractTemplate;
use App\Models\ContractArticle;

class ContractTemplateController extends Controller
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
        $contract_templates = ContractTemplate::orderBy('name','ASC')->get();

		return view('Centaur::contract_templates.index', ['contract_templates' => $contract_templates]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::contract_templates.create');
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
			'name'  		        => $request['name'],
			'general_conditions'  	=> $request['general_conditions'],
            
		);
      
        $contractTemplate = new ContractTemplate();
        $contractTemplate->saveContractTemplate($data);
        
        if( count(  $request['article_text']) > 0 ) {
            foreach ( $request['article_text'] as $article ) {
                if ($article && $article != '') {
                    $data_article = array(
                        'template_id'  => $contractTemplate->id,
                        'article_text' => $article,
                    );
                    $contractArticle = new ContractArticle();
                    $contractArticle->saveContractArticle($data_article);
                }
            }
        }
	
        session()->flash('success',  __('ctrl.data_save'));
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
        $contract_template = ContractTemplate::find($id);
        
        return view('Centaur::contract_templates.edit', ['contract_template' => $contract_template]);
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
        $contractTemplate = ContractTemplate::find($id);

        $data = array(
			'name'  		      => $request['name'],
            'general_conditions'  => $request['general_conditions'],
		);
      
        $contractTemplate->updateContractTemplate($data);

        if( count( $request['article_text']) > 0 ) {
            foreach ( $request['article_text'] as $key => $article_text ) {
                $contractArticle = null;
                $article_id = isset($request['article_id'][$key]) ? $request['article_id'][$key] : null;
                if( $article_id ) {
                    $contractArticle = ContractArticle::find($article_id);
                }

                $data_article = array(
                    'template_id'  => $contractTemplate->id,
                    'article_text' => $article_text,
                );

                if( $contractArticle ) {
                    $contractArticle->updateContractArticle($data_article);
                } else {
                    $contractArticle = new ContractArticle();
                    $contractArticle->saveContractArticle($data_article);
                }
            }
        }

        session()->flash('success',  __('ctrl.data_edit'));
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
        //
    }
}
