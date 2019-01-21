<?php

namespace RepMap\Http\Middleware;

use Illuminate\Support\Facades\View;
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

		View::share('viewState', (Object) $state);

        return $next($request);
    }
}
