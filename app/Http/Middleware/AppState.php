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

		$data = Constituency::with([
			'county',
			'electedMember',
			'electedMember.party',
			'issueStances'
		])
		->get()
		->sortBy('name')
		->values()
		->keyBy('cty16cd')
		->toArray();

		$state['map'] = [
			'constituencies' => $data,
			'counties' => array_pluck($data, 'county', 'county.id'),
			'parties' => array_reduce($data, function($arr, $constituency) {
				$member = $constituency['elected_member'];

				$arr[$member['party']['id']] = $member['party'];
				return $arr;
			}, [])
		];

		View::share('viewState', (Object) $state);

        return $next($request);
    }
}
