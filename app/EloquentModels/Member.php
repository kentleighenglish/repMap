<?php

namespace RepMap\EloquentModels;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{

		/**
	     * @var string
	     */
	    protected $table = 'members';


	    /**
	     * @var array
	     */
	    protected $fillable = [ 'fullname', 'party_id', 'constituency_id', 'webpage', 'twitter', 'elected', 'representation' ];

		public function party()
		{
			return $this->belongsTo('RepMap\EloquentModels\Party');
		}

		public function constituency()
		{
			return $this->belongsTo('RepMap\EloquentModels\Constituency');
		}

		public function issueStances()
		{
			return $this->hasMany('RepMap\EloquentModels\IssueStance');
		}

}
