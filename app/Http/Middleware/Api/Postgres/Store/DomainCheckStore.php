<?php


namespace App\Http\Middleware\Api\Postgres\Store;

use Closure;

class DomainCheckStore
{
    protected $arrDomain = [];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

//        $origin = $request->headers->get('Origin');
//
////        $is_ajax = $request->ajax();
//
//        if (!in_array($origin, $this->arrDomain) /*|| !$is_ajax*/)
//        {
//
//            return response()->json(['error_code'=> 7,'message'=>'Permission denied.Please check your permission.', 'data' => ['checkDomain']])->setStatusCode(500);
//        }
        return $next($request);
    }
}
