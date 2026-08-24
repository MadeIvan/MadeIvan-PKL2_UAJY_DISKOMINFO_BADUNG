<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SqlInjectionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        array_walk_recursive($input, function ($value) {
            if (is_string($value) && $this->hasSqlInjection($value)) {
                abort(403, 'Potential SQL Injection Detected.');
            }
        });

        return $next($request);
    }

    /**
     * Check if the given string contains potential SQL injection patterns.
     */
    private function hasSqlInjection(string $value): bool
    {
        $patterns = [
            // Matches typical UNION SELECT or similar chained statements
            '/(?:\b(union|select|insert|update|delete|drop|alter|truncate|rename|exec|declare|cast)\b.*\b(from|into|table|database|view|index)\b)/i',
            // Matches typical tautologies like ' OR '1'='1 or " OR 1=1
            '/\b(or|and)\b\s+[\'"]?\w+[\'"]?\s*(=|LIKE|>|<|IN|IS)\s*[\'"]?\w+[\'"]?/i',
            // Matches stacked queries
            '/(?:;.*(?:union|select|insert|update|delete|drop|alter|truncate|rename|exec|declare|cast))/i',
            // Matches common SQLi sleep/benchmark functions
            '/\b(sleep|benchmark|pg_sleep|waitfor\s+delay)\b\s*\(/i'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }

        return false;
    }
}
