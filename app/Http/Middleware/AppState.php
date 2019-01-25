<?php

namespace RepMap\Http\Middleware;

use Illuminate\Support\Facades\View;
use RepMap\EloquentModels\Constituency;
use Closure;

class AppState
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$state  = [];

		$data = Constituency::all()
		->sortBy('name')
		->values()
		->load([
			'county',
			'members' => function($query) {
				$query
				->where('elected', 1);
			},
			'members.party',
			'issueStances'
		])
		->keyBy('cty16cd')
		->toArray();

		$state['map'] = [
			'constituencies' => $data,
			'counties' => array_reduce($data, function($arr, $constituency) {
				$arr[$constituency['county']['id']] = $constituency['county'];

				return $arr;
			}, [])
		];

		View::share('viewState', (Object) $state);

        return $next($request);
    }
}
