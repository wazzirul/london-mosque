<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Model\AuthenticationToken;

class JwtAuthentication
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
        $auth_token = $request->header('auth_token') ?? null;
        $carbon_now = Carbon::now();

        if($auth_token === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Authorization Token not found";

            return response($res, 406);
        }

        $find_token = AuthenticationToken::where('auth_token', $auth_token)
            ->first();

        if($find_token === null){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Token is Invalid";

            return response($res, 406);
        }

        if($find_token->expired < $carbon_now){
            $res['status_code'] = 406;
            $res['success'] = false;
            $res['detail'] = "Token is Expired";

            return response($res, 406);
        }
        
        return $next($request);
    }
}
