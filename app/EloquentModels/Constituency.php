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

}
