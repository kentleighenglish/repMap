<?php

namespace RepMap\EloquentModels;

use Illuminate\Database\Eloquent\Model;

class County extends Model
{

	/**
	 * @var string
	 */
	protected $table = 'counties';


	/**
	 * @var array
	 */
	protected $fillable = [ 'name', 'cty18cd', 'representation' ];

	public function constituencies()
	{
		return $this->hasMany('RepMap\EloquentModels\Constituency');
	}
}
