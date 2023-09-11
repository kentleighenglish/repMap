<?php

namespace RepMap\EloquentModels;

use Illuminate\Database\Eloquent\Model;

class Geometry extends Model
{

	/**
	 * @var string
	 */
	protected $table = 'geometry';


	/**
	 * @var array
	 */
	protected $fillable = [ 'constituency_id', 'geojson' ];

	public function constituency()
	{
		return $this->belongsTo('RepMap\EloquentModels\Constituency');
	}

}
