<?php

namespace RepMap\EloquentModels;

use Illuminate\Database\Eloquent\Model;

class IssueStance extends Model
{

		/**
	     * @var string
	     */
	    protected $table = 'issue_stances';


	    /**
	     * @var array
	     */
	    protected $fillable = [ 'constituency_id', 'member_id', 'issue_id', 'electorate', 'turnout', 'member_representing', 'member_stance', 'constituency_stance' ];

		public function constituency()
		{
			return $this->belongsTo('RepMap\EloquentModels\Constituency');
		}

		public function issue()
		{
			return $this->belongsTo('RepMap\EloquentModels\Issue');
		}

		public function member()
		{
			return $this->belongsTo('RepMap\EloquentModels\Member');
		}
}
