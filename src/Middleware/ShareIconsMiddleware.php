<?php

declare(strict_types=1);

namespace Cat4year\SvgReuserLaravel\Middleware;

use Cat4year\SvgReuserLaravel\Services\IconService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class ShareIconsMiddleware
{
    public function __construct(
        private IconService $iconService,
    ) {
    }

    /**
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->iconService->loadSprite();

        return $next($request);
    }
}
