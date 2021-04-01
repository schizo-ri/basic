<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetenceEvaluation extends Model
{
   /**
     * The attributes thet are mass assignable
     *
     * @var array
     */
    protected $fillable = ['user_id','competence_id','employee_id','evaluation_date','question_id','rating_id','comment'];

    /*
	* The Eloquent Employee model name
	* 
	* @var string
	*/
	protected static $employeeModel = 'App\Models\Employee'; 

    /*
	* The Eloquent CompetenceQuestion model name
	* 
	* @var string
	*/
	protected static $questionModel = 'App\Models\CompetenceQuestion'; 

    /*
	* The Eloquent CompetenceRating model name
	* 
	* @var string
	*/
	protected static $ratingModel = 'App\Models\CompetenceRating'; 

    /*
	* Returns the Employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function user()
	{
		return $this->belongsTo(static::$employeeModel,'user_id');
	}

    /*
	* Returns the Employee relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function employee()
	{
		return $this->belongsTo(static::$employeeModel,'employee_id');
	}

    /*
	 * Returns the CompetenceQuestion relationship
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function question()
	{
		return $this->belongsTo(static::$questionModel,'question_id');
	}

    /*
	 * Returns the CompetenceRating relationship
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function rating()
	{
		return $this->belongsTo(static::$ratingModel,'rating_id');
	}

    /*
	* Save CompetenceEvaluation
	* 
	* @param array $competenceEvaluation
	* @return void
	*/
	
	public function saveCompetenceEvaluation ($competenceEvaluation=array())
	{
		return $this->fill($competenceEvaluation)->save();
	}
	
	/*
	* Update CompetenceEvaluation
	* 
	* @param array $competenceEvaluation
	* @return void
	*/
	
	public function updateCompetenceEvaluation($competenceEvaluation=array())
	{
		return $this->update($competenceEvaluation);
	}
}
