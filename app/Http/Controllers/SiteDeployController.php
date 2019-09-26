<?php

namespace App\Http\Controllers;

use App\Services\SiteManager;
use App\Jobs\SiteDeploy;
use \App\Http\Middleware\SiteHandler;

class SiteDeployController extends Controller
{
    public function __construct()
    {
        $this->middleware(SiteHandler::class);
    }

    public function deploySite()
    {
        echo '.... start deploying .... ' . "</br>";
        $newSite = app(SiteManager::class)->getSite();
        if(empty($newSite)){
            dd('Invalid Site');
        }
        echo 'Job Executed';
        SiteDeploy::dispatch($newSite, app(\App\Services\SiteManager::class));

    }
}
