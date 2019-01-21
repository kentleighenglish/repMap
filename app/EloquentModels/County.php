<?php

namespace RepMap;

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
	    protected $fillable = [ 'name', 'representation' ];

}
