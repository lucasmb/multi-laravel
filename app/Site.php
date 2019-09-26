<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    //

    public function route($name, $parameters = []) {
        $host = $this->domain ?? $this->subdomain;
        return 'http://' . $host . app('url')->route($name, $parameters, false);
    }

}
