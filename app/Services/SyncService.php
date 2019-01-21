<?php

namespace RepMap\Services;

use RepMap\Services\ParliamentApi;
use RepMap\Services\ONSApi;

use RepMap\County;
use RepMap\Constituency;

class SyncService {

	public $parliament;
	public $ons;

	public function __construct(ParliamentApi $parliament, ONSApi $ons)
	{
		$this->parliament = $parliament;
		$this->ons = $ons;
	}

	public function updateCounties()
	{
		$results = $this->ons->getActiveCounties();

		//@todo get counties to save in bulk, only name prop... same for constituents below
	}

	public function updateConstituencies()
	{
		$results = $this->ons->getActiveConstituencies();

		var_dump($results);
		// $data = array_reduce(function($arr, $c) {
		// 	$item = [
		// 		"name" => $c->label->_value,
		// 		"type" => $c->constituencyType,
		// 		"cty16cd" => $c->gssCode
		// 	];
		//
		// 	$arr[] = $item;
		// }, []);

		// Constituency($c);
	}

}
