<?php

namespace RepMap\Http\Middleware;

use Illuminate\Support\Facades\View;
use RepMap\EloquentModels\Constituency;
use RepMap\Services\AbstractApi;
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

		$extraMaps = config('props.geoJsonExtra');
		$api = new AbstractApi();
		$nonconstituencies = [];

		foreach($extraMaps as $url) {
			$response = $api->get($url);

			if ($response['statusCode'] === 200) {
				$collection = $response['data'];
				$nonconstituencies[] = $collection;
			}
		}

		$state['map'] = [
			'constituencies' => $data,
			'extra' => $nonconstituencies,
			'counties' => array_values(array_pluck($data, 'county', 'county.id')),
			'parties' => array_values(array_reduce($data, function($arr, $constituency) {
				$member = $constituency['elected_member'];

				$arr[$member['party']['id']] = $member['party'];
				return $arr;
			}, []))
		];

		View::share('viewState', (Object) $state);

		return $next($request);
	}
}
