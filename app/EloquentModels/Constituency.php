<?php

namespace RepMap\EloquentModels;

use Illuminate\Database\Eloquent\Model;

class Constituency extends Model
{

	/**
	 * @var string
	 */
	protected $table = 'constituencies';


	/**
	 * @var array
	 */
	protected $fillable = [ 'name', 'cty16cd', 'geojson', 'representation' ];

	public function county()
	{
		return $this->belongsTo('RepMap\EloquentModels\County');
	}

	public function geometry()
	{
		return $this->hasOne('RepMap\EloquentModels\Geometry');
	}

	public function members()
	{
		return $this->hasMany('RepMap\EloquentModels\Member');
	}

	public function electedMember()
	{
		return $this->hasOne('RepMap\EloquentModels\Member');
	}

	public function issueStances()
	{
		return $this->hasMany('RepMap\EloquentModels\IssueStance');
	}

}
