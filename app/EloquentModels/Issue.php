<?php

namespace RepMap;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{

		/**
	     * @var string
	     */
	    protected $table = 'issues';


	    /**
	     * @var array
	     */
	    protected $fillable = [ 'name', 'representation' ];

}
