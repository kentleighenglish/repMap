<?php

namespace RepMap;

use Illuminate\Database\Eloquent\Model;

class Constituency extends Model
{

	/**
     * @var string
     */
    protected $table = 'constituencies';


    /**
     * @var array
     */
    protected $fillable = [ 'county_id', 'name', 'type', 'gsscode', 'geojson', 'representation' ];

}
