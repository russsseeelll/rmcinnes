<?php

namespace App\Http\Middleware;

use App\Support\AnonymousIp;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenTelemetry\API\Trace\Span;
use Symfony\Component\HttpFoundation\Response;

class RecordRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        $client = AnonymousIp::of($request->ip());
        $span = Span::getCurrent();
        $traceId = $span->getContext()->isValid() ? $span->getContext()->getTraceId() : null;

        $span->setAttribute('client.address', $client);
        $span->setAttribute('http.client_ip', $client);
        $span->setAttribute('net.peer.ip', $client);
        $request->attributes->set('client_anonymised', $client);
        $request->attributes->set('trace_id', $traceId);

        $started = hrtime(true);
        $response = $next($request);

        Log::info('request', [
            'event' => 'request',
            'method' => $request->method(),
            'path' => '/'.ltrim($request->path(), '/'),
            'route' => $request->route()?->uri(),
            'status' => $response->getStatusCode(),
            'duration_ms' => round((hrtime(true) - $started) / 1e6, 1),
            'client' => $client,
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 200),
            'trace_id' => $traceId,
        ]);

        return $response;
    }
}
