<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Add is_teacher attribute to the request for it to be accesible all around my controllers
        $request->merge([
            'is_teacher' => auth()->check() ? auth()->user()->isTeacher() : false
        ]);
        return $next($request);
    }
}
