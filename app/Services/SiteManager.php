<?php
/**
 * Created by PhpStorm.
 * User: lucas
 * Date: 1/23/19
 * Time: 10:13 AM
 */

namespace App\Services;
use App\Site;
use Illuminate\Support\Facades\Cache;

class SiteManager
{
    private $site;

    public function setSite(?Site $site) {
        $this->site = $site;
        return $this;
    }

    public function  getSite(): ?Site {
        return $this->site;
    }

    public function loadSite(string $identifier, bool $subdomain): bool
    {
       //var_dump('loader:');
       $site = Site::query()->where(($subdomain) ? 'subdomain' : 'domain', '=', $identifier)->first();

        if ($site) {
            $this->setSite($site);

            $config = getSessionConfig($this->site->id);
            if($config){
                // Cache::forever('config', $config);
                Cache::put('config', $config);
            }
            return true;
        }

        return false;
    }

    public function loadConfig($siteID){
        getConfig($this->site->id);
    }


}
