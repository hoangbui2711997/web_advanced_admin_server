<?php

namespace App\Http\Middleware;

use Closure;

class BuildApiResponse
{
    protected $except = [
        'api/oauth/token',
        'api/chart/*',
        '**/export'
    ];

    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        foreach ($this->except as $url) {
            if ($request->path() === $url || $request->is($url)) {
                return $response;
            }
        }

        $original = $response->getOriginalContent();

        if (!empty(data_get($original, 'exception'))) {
            return response()->json(['error' => data_get($original, 'message')], $response->status());
        }

        if (!empty(data_get($original, 'errors'))) {
            return response()->json($original, $response->status());
        }

        if (!empty(data_get($original, 'message'))) {
            return response()->json(['error' => data_get($original, 'message')], $response->status());
        }

        return response()->json([
            'data' => $original
        ], $response->status());
    }
}
