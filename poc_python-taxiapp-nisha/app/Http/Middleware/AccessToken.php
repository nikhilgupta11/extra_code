<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\AuthToken;
use Illuminate\Http\Request;

class AccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $auth_token = AuthToken::first();

        if (empty($request->header('Token'))) {
            return response('Please Provide token.', 401);
        } else {
            if ($request->header('Token') != $auth_token->token) {

                return response('Not valid token provider.', 401);
            } else {
                return $next($request);
            }
        }
    }
}
