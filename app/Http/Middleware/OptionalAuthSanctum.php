<?php

namespace App\Http\Middleware;

use App\Services\ErrorResponder\ErrorResponder;
use App\Services\ErrorResponder\ResponseError;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OptionalAuthSanctum
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard = null): Response
    {
        /** @var ErrorResponder $errorResponder */
        $errorResponder = App::make(ErrorResponder::class);

        if ($request->bearerToken() !== null && !Auth::guard($guard)->check()) {
            return $errorResponder->makeByError(ResponseError::AuthenticationFailed);
        }

        $request->setUserResolver(fn () => Auth::guard($guard)->user());

        return $next($request);
    }

}
