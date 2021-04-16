<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'product_number','name','price','quantity','unit','manufacturer_id'];

    /*
	* The Eloquent Manufacturer model name
	* 
	* @var string
	*/
	protected static $manufacturerModel = 'App\Models\Manufacturer'; 

    /*
	* The Eloquent DischargeStock model name
	* 
	* @var string
	*/
	protected static $dischargeModel = 'App\Models\DischargeStock'; 

    /*
	* Returns the user relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function manufacturer()
	{
		return $this->belongsTo(static::$manufacturerModel,'manufacturer_id');
	}


    /*
	* Returns the DischargeStock relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\hasmany
	*/
	
	public function hasDischarges()
	{
		return $this->hasMany(static::$dischargeModel,'item_id');
	}
    /*
    * Save Stock
    * 
    * @param array $stock
    * @return void
    */
    public function saveStock($stock=array())
    {
        return $this->fill($stock)->save();
    }
    
    /*
    * Update Stock
    * 
    * @param array $stock
    * @return void
    */
    
    public function updateStock($stock=array())
    {
        return $this->update($stock);
    }	
}
