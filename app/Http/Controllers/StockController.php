<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\DischargeStock;
use App\Models\Manufacturer;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StockImport;
use Illuminate\Support\Facades\Mail;
use App\Mail\ErrorMail;

class StockController extends Controller
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
        $stock = Stock::get();
        $dischargeStock = DischargeStock::get();
        $manufacturers = Manufacturer::orderBy('name','ASC')->get();
      
        $sum_discharge = 0;
        $sum = 0;
        $sum_missing = 0;
        $sum_damaged = 0;

        if( count($stock ) > 0) {
            $sum = $stock->map(function ($item, $key) {
                return $item->quantity * $item->price;
            });
            $sum = $sum->sum();
        }
        if( count( $dischargeStock ) > 0) {
            $sum_discharge = $dischargeStock->map(function ($item, $key) {
                if( $item->missing != 1 && $item->damaged != 1 ) {
                    return $item->quantity * $item->stock->price;
                }
            });
            $sum_discharge = $sum_discharge->sum();

            $dischargeStock_miss = $dischargeStock->where('missing', 1);
            $sum_missing = $dischargeStock_miss->map(function ($item, $key) {
                    return $item->quantity * $item->stock->price;
            });
            $sum_missing = $sum_missing->sum();
            
            $dischargeStock_damg = $dischargeStock->where('damaged', 1);
            $sum_damaged = $dischargeStock_damg->map(function ($item, $key) {
                    return $item->quantity * $item->stock->price;
            });
            $sum_damaged = $sum_damaged->sum();
        }
       
        $total_sum = $sum - $sum_discharge - $sum_missing - $sum_damaged;

        return view('Centaur::stocks.index',['stock' => $stock, 'manufacturers' => $manufacturers,'sum_discharge' => $sum_discharge,'sum' => $total_sum,'sum_missing' => $sum_missing,'sum_damaged' => $sum_damaged]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $manufacturers = Manufacturer::orderBy('name','ASC')->get();
      
        return view('Centaur::stocks.create',['manufacturers' => $manufacturers]);
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
            'manufacturer_id'  => $request['manufacturer_id'],
            'name'          => $request['name'],
            'product_number' => $request['product_number'],
            'price'         => $request['price'],
            'quantity'      => $request['quantity'],
            'unit'          => $request['unit'],
        );
     
        $item = new Stock();
        $item->saveStock($data);
        
        session()->flash('success', "Podaci su spremljeni");
        
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
        $item = Stock::find($id);
        $dischargeStock = $item->hasDischarges;

        return view('Centaur::stocks.show',['item' => $item, 'dischargeStock' => $dischargeStock ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Stock::find($id);
        $manufacturers = Manufacturer::orderBy('name','ASC')->get();
      
        return view('Centaur::stocks.edit',['item' => $item,'manufacturers' => $manufacturers ]);
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
        $item = Stock::find($id);

        $data = array(
            'manufacturer_id' => $request['manufacturer_id'],
            'name'          => $request['name'],
            'product_number' => $request['product_number'],
            'price'         => $request['price'],
            'quantity'      => $request['quantity'],
            'unit'          => $request['unit'],
        );
     
        $item->updateStock($data);
        
        session()->flash('success', "Podaci su spremljeni");
        
        return redirect()->back();
    }

    /** INLINE EDITING */
    public function updateStock(Request $request)
    {
        $item = Stock::find($request['id']);
      
        if(isset($request['manufacturer_id'])) {
            $data = array('manufacturer_id' => $request['manufacturer_id']);
        } 
  
        $item->updateStock($data);
        
        return "sve ok";
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Stock::find($id);
        $item->delete();

        session()->flash('success', "Podaci su obrisani");
        
        return redirect()->back();
    }

    public function importStock (Request $request) 
    {
        try {
            Excel::import(new StockImport, request()->file('file'));
        } catch (\Throwable $th) {
            $email = 'jelena.juras@duplico.hr';
            $url = $_SERVER['REQUEST_URI'];
            Mail::to($email)->send(new ErrorMail($th->getMessage(), $url)); 
            
            session()->flash('error', "Došlo je do problema, dokument nije učitan! " . $th->getMessage());
        
            return redirect()->back();
        } 
       
        session()->flash('success', "Dokument je učitan");
        return back();
    }

}
