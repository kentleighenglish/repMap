<?php

namespace RepMap\Http\Controllers;

use RepMap\Http\Controllers\Controller;

use RepMap\Services\SyncService;

class PageController extends Controller
{

	public $sync;

	public function __construct(SyncService $sync)
	{
		$this->sync = $sync;
	}

	/**
	 * Show the index of the website
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		// $this->sync->updateCounties();
		// $this->sync->updateConstituencies();
		// $this->sync->updateMembers();
		// $this->sync->updateGeoJson();

		return view('index');
	}

}
