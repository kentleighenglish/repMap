<?php

namespace RepMap\Http\Controllers;

use RepMap\Http\Controllers\Controller;

class PageController extends Controller
{

	/**
	 * Show the index of the website
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		return view('index');
	}

}
