<?php

namespace RepMap;

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

}
