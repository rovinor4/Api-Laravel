<?php

namespace App\Http\Middleware;

use App\Models\Societies;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $valid = $request->validate(["token" => "required"]);

            $find = Societies::where("login_tokens", $valid["token"])->first();
            if ($find) {
                return $next($request);
            } else {
                return response()->json(["message" => "Unauthorized user"], 401);
            }
        } catch (\Exception $ms) {
            return response()->json(["message" => $ms->getMessage()]);
        }
    }
}
