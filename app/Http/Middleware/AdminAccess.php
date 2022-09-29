<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;


use Illuminate\Support\Facades\Auth;


class AdminAccess
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
        $rolesitem = ["admin"];
        $user = Auth::user();       
       // dd($user); 
        if (in_array($user->role, $rolesitem)) {
          return $next($request);

        }
        $response = [
          "message" => "You are unauthorized to perfomed the action",
          "status" => false,
          "data" => []
        ];
      return response($response, 401);
    }
}
