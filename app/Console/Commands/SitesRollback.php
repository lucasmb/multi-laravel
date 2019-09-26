<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SiteManager;
use App\Site;

class SitesRollback extends Command
{
    protected $signature = 'sites:rollback
                            {--step= : Whether the job should be queued}';

    protected $description = 'Rollback Sites databases';

    protected $siteManager;

    protected $migrator;

    public function __construct(SiteManager $siteManager)
    {
        parent::__construct();

        $this->siteManager = $siteManager;
        $this->migrator = app('migrator');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sites = Site::all();

        $option = $this->option();
        //var_dump($option);
        //dd($this->option() );

        foreach ($sites as $site) {
            $this->siteManager->setSite($site);
            \DB::connection('site')->reconnect();
            $this->rollback($option);
        }
    }


    private function rollback($option = array()) {
        $this->prepareDatabase();
        $this->migrator->rollback(database_path('migrations/sites'), $option);

        $this->migrator->setOutput($this->output);
        // foreach ($this->migrator->getNotes() as $note) {
        //     $this->output->writeln($note);
        // }
    }

    protected function prepareDatabase() {
        $this->migrator->setConnection('site');

        if (! $this->migrator->repositoryExists()) {
            $this->call('migrate:install');
        }
    }
}
