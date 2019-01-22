<?php

namespace RepMap\EloquentModels;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{

		/**
	     * @var string
	     */
	    protected $table = 'parties';


	    /**
	     * @var array
	     */
	    protected $fillable = [ 'name', 'colour', 'representation' ];

		public function members()
		{
			return $this->hasMany('RepMap\EloquentModels\Member');
		}

}
