<?php

namespace App\Jobs;

use App\Services\SiteManager;
use App\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class SiteDeploy implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable;

    protected $site;
    protected $siteManager;

    public function __construct(Site $site, SiteManager $siteManager) {
        $this->site        = $site;
        $this->siteManager = $siteManager;
    }

    public function handle() {
        $database    = 'site_' . $this->site->code;

        $connection  = \DB::connection('site');
        $createMysql = \DB::connection('mysql')->getPdo()->exec("CREATE DATABASE IF NOT EXISTS `{$database}`");

        if ($createMysql) {
            $this->siteManager->setSite($this->site);
            $connection->reconnect();
            $this->migrate();
        } else {
            //$connection->statement('DROP DATABASE ' . $database);
        }
    }

    private function migrate() {
        $migrator = app('migrator');
        $migrator->setConnection('site');

        if (! $migrator->repositoryExists()) {
            $migrator->getRepository()->createRepository();
        }

        $migrator->run(database_path('migrations/sites'), []);
    }
}


/*

class TenantDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct()
    {
        //
    }

    public function handle()
    {
        //
    }
}

*/
