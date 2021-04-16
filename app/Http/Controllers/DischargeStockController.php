<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DischargeStock;
use App\Models\Preparation;
use App\Models\Stock;
use App\Exports\DischargeStockExport;
use App\Exports\DischargeStockExport2;
use App\Mail\ErrorMail;
use Maatwebsite\Excel\Facades\Excel;
use Sentinel;

class DischargeStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $stock_item = null;
        if( isset( $request['stock_id']) ) {
            $stock_item = Stock::find( $request['stock_id'] );
        }
        $stocks = Stock::get();
        $preparations = Preparation::where('active',1)->get();
        
        return view('Centaur::discharge_stocks.create',['stocks' => $stocks, 'preparations' => $preparations, 'stock_item' => $stock_item ]);
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
            'preparation_id'  => $request['preparation_id'],
            'user_id'      => Sentinel::getUser()->id,
            'item_id'      => $request['item_id'],
            'quantity'     => $request['quantity'],
            'comment'      => $request['comment'],
            'missing'      => $request['missing'] ? $request['missing'] : null,
            'damaged'      => $request['damaged'] ? $request['damaged'] : null,
        );
     
        $dischargeStock = new DischargeStock();
        $dischargeStock->saveDischargeStock($data);
        
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
        $stock_item = DischargeStock::find( $id );
        $stocks = Stock::get();
        $preparations = Preparation::where('active',1)->get();
        
        return view('Centaur::discharge_stocks.edit',['stocks' => $stocks, 'preparations' => $preparations, 'stock_item' => $stock_item ]);
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
        $dischargeStock = DischargeStock::find( $id );

        $data = array(
            'preparation_id'  => $request['preparation_id'],
            'user_id'      => Sentinel::getUser()->id,
            'item_id'      => $request['item_id'],
            'quantity'     => $request['quantity'],
            'comment'      => $request['comment'],
            'missing'      => $request['missing'] ? $request['missing'] : null,
            'damaged'      => $request['damaged'] ? $request['damaged'] : null,
        );
     
        $dischargeStock->updateDischargeStock($data);
        
        session()->flash('success', "Podaci su spremljeni");
        
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
        $dischargeStock = DischargeStock::find( $id );
        $dischargeStock->delete();
        
        session()->flash('success', "Podaci su obrisani");
        
        return redirect()->back();

    }

    public function exportStock($id) 
    {
        $preparation = Preparation::find( $id );
        $dischargeStock =  $preparation->hasDischargeStock;
       
        $lists = array();

        foreach ($dischargeStock as $item) {
            $data = array(
                $item->stock->product_number,
                $item->stock->name,
                $item->quantity,
            );
            array_push( $lists, $data);
        }
     
        $export = new DischargeStockExport($lists);
        
        return Excel::download($export, 'export_onStock.xlsx');
    }

    public function exportStock2($id) 
    {
        $preparation = Preparation::find( $id );
        $equipmentList = $preparation->equipment;
        $dischargeStock =  $preparation->hasDischargeStock;

        $lists = array();
        
        foreach ($equipmentList as $item) {
            $stock_item =  Stock::where('product_number', $item->product_number)->first();
          
            $discharge_sum = 0;
            if( $stock_item ) {
                $discharge_sum = $dischargeStock->where('item_id', $stock_item->id)->sum('quantity');
            }
          
            $quantity = $item->quantity - $discharge_sum;

            if($quantity > 0) {
                $data = array(
                    $item->product_number,
                    $item->name,
                    $item->unit,
                    $quantity,
                );
                array_push( $lists, $data);
            }
        }
       
        $export = new DischargeStockExport2($lists);
        
        return Excel::download($export, 'export_order.xlsx');
    }
}
