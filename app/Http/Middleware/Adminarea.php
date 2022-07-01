<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure; 
use Session;
class Adminarea
{
	
	public function handle($request,Closure $next)
	{
		if(@Auth::user()->rule=='admin')
		{
			return $next($request);
		} 
	 	else
	 	{
	 		return redirect('/');
	 	}
	}
}
