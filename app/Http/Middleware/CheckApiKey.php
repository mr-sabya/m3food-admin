<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the API key from the request header (e.g., 'X-API-KEY' or 'api_key')
        // You can choose any header name you prefer.
        $apiKey = $request->header('X-API-KEY');

        // Retrieve your expected API key(s) from environment variables.
        // For multiple keys, you might store them as a comma-separated string
        // or in a dedicated database table.
        $validApiKeys = explode(',', env('API_FRONTEND_KEYS', '')); // Allow multiple keys

        if (empty($apiKey) || !in_array($apiKey, $validApiKeys)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized: Invalid or missing API key.',
            ], 401);
        }

        return $next($request);
    }
}
