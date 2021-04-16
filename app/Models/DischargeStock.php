<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DischargeStock extends Model
{
    protected $fillable = [
        'user_id','item_id','quantity', 'preparation_id', 'comment', 'missing', 'damaged'];

    /*
	* The Eloquent stock model name
	* 
	* @var string
	*/
	protected static $stockModel = 'App\Models\Stock'; 

    /*
	* The Eloquent preparation model name
	* 
	* @var string
	*/
	protected static $preparationModel = 'App\Models\Preparation'; 

     /*
	* The Eloquent project model name
	* 
	* @var string
	*/
	protected static $userModel = 'App\User'; 

    /*
	* Returns the user relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function user()
	{
		return $this->belongsTo(static::$userModel,'user_id');
	}

    /*
	* Returns the stock relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function stock()
	{
		return $this->belongsTo(static::$stockModel,'item_id');
    }

     /*
	* Returns the preparation relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	public function preparation()
	{
		return $this->belongsTo(static::$preparationModel,'preparation_id');
    }


    /*
    * Save DischargeStock
    * 
    * @param array $dischargeStock
    * @return void
    */
    public function saveDischargeStock($dischargeStock=array())
    {
        return $this->fill($dischargeStock)->save();
    }
    
    /*
    * Update DischargeStock
    * 
    * @param array $dischargeStock
    * @return void
    */
    
    public function updateDischargeStock($dischargeStock=array())
    {
        return $this->update($dischargeStock);
    }	
}
