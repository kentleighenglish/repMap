<?php

namespace RepMap\EloquentModels;

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


		public function issueStances()
		{
			return $this->hasMany('RepMap\EloquentModels\IssueStance');
		}

}
