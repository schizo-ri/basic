<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetenceDepartment extends Model
{
    /**
     * The attributes thet are mass assignable
     *
     * @var array
     */
    protected $fillable = ['competence_id','work_id','department_id'];

    /*
	* The Eloquent Competence model name
	* 
	* @var string
	*/
	protected static $competenceModel = 'App\Models\Competence'; 

     /*
	* The Eloquent Work model name
	* 
	* @var string
	*/
	protected static $workModel = 'App\Models\Work'; 

    /*
	* The Eloquent Department model name
	* 
	* @var string
	*/
	protected static $departmentModel = 'App\Models\Department'; 

    /*
	* Returns the Competence relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function competence()
	{
		return $this->belongsTo(static::$competenceModel,'competence_id');
	}

    /*
	* Returns the Department relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function department()
	{
		return $this->belongsTo(static::$departmentModel,'department_id');
	}

    /*
	* Returns the Department relationship
	* 
	* @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	*/
	
	public function work()
	{
		return $this->belongsTo(static::$workModel,'work_id');
	}

    /*
	* Save CompetenceDepartment
	* 
	* @param array $competenceDepartment
	* @return void
	*/
	
	public function saveCompetenceDepartment ($competenceDepartment=array())
	{
		return $this->fill($competenceDepartment)->save();
	}
	
	/*
	* Update CompetenceDepartment
	* 
	* @param array $competenceDepartment
	* @return void
	*/
	
	public function updateCompetenceDepartment($competenceDepartment=array())
	{
		return $this->update($competenceDepartment);
	}
}
