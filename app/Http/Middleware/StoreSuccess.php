<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class Cors
 * add Access-Control headers for API requests from outside (axios)
 * and handle the preflight request OPTION-method
 * @package App\Http\Middleware
 */
class StoreSuccess
{
    private $_valideSubmitValues = [
        'save',
        'saveAndBack',
    ];
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if( in_array($request->submit, $this->_valideSubmitValues) ) {
//             return $next($request)->with('success','Datensatz erfolgreich gespeichert!');
        }
        return $next($request);
    }
}
?>