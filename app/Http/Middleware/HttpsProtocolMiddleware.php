<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class HttpsProtocolMiddleware
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
        if(is_null(session('locale')))
        {
            session(['locale'=> "hr"]);
        }
        app()->setLocale(session('locale'));

        if (!$request->secure() &&  App::environment() != 'local') {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
