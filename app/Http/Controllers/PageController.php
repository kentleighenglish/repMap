<?php

namespace RepMap\Http\Controllers;

use RepMap\Http\Controllers\Controller;

class PageController extends Controller
{

	public function __construct()
	{
	}

	/**
	 * Show the index of the website
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		return view('index');
	}

	/**
	 * Show the admin page of the website
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function admin()
	{
		return view('admin');
	}

}
