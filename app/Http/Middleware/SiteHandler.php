<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\SiteManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class SiteHandler
{
    protected $siteManager;

    public function __construct(SiteManager $siteManager)
    {
        $this->siteManager = $siteManager;
    }

    public function handle($request, Closure $next)
    {
        $host = $request->getHost();
        $pos = strpos($host, strval(env('MAIN_DOMAIN')) );

        if ($this->siteManager->loadSite($pos !== false ? substr($host, 0, $pos - 1) : $host, $pos !== false)) {

            return $next($request);
        }


        throw new NotFoundHttpException;
    }
}
