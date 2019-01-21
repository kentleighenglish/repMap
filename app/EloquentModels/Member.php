<?php

namespace RepMap;

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

}
