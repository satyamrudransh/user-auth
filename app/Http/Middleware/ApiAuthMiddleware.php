<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the bearer token from the Authorization header
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'No token provided'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Validate the token (example logic)
        if (!$this->validateToken($token)) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'Invalid or expired token'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Token is valid, proceed with the request
        return $next($request);
    }

    /**
     * Validate the token.
     *
     * @param  string  $token
     * @return bool
     */
    private function validateToken($token)
    {
        // Find the token record by its value (token string)
        $tokenRecord = DB::table('oauth_access_tokens')->where('id', $token)->first();

        // Check if the token record exists and is not expired
        return $tokenRecord && !$this->isTokenExpired($tokenRecord);
    }

    /**
     * Check if the token is expired.
     *
     * @param  object  $tokenRecord
     * @return bool
     */
    private function isTokenExpired($tokenRecord)
    {
        // Example expiration check
        $now = Carbon::now();
        return $now->greaterThan($tokenRecord->expires_at);
    }
}
