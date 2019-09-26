<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SiteManager;
use App\Site;

class SiteMigrate extends Command
{
    protected $signature = 'sites:migrate';
    protected $description = 'Migrate Sites databases';
    protected $siteManager;

    protected $migrator;

    public function __construct(SiteManager $siteManager) {
        parent::__construct();
        $this->siteManager = $siteManager;
        $this->migrator = app('migrator');
    }

    public function handle() {
        $sites = Site::all();

        foreach ($sites as $site) {
            $this->siteManager->setSite($site);
            \DB::connection('site')->purge();
            $this->migrate();
        }
    }

    private function migrate() {
        $this->prepareDatabase();
        $this->migrator->run(database_path('migrations/sites'), []);
    }

    protected function prepareDatabase() {
        $this->migrator->setConnection('site');

        if (! $this->migrator->repositoryExists()) {
            $this->call('migrate:install');
        }
    }
}
