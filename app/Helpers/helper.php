<?php
use App\Services\SiteManager;
use App\Models\Setting;

function currentSite()
{
   // var_dump('current site from helper: ');
    return app('App\Services\SiteManager')->getSite();
}


function apiUrl(){
   return getConfigItem('api_url');
}

function getConfigItem($item){
    $config = \Cache::get('config', false);
    $found = (isset($config[$item]) ) ? $config[$item]->value : false;
    return $found;
}

/***
* get settings on a plain array
**/
function getSessionConfig($site_id){
    //all settings for site id function ($query) use ($activated) {
    $siteSettings = Setting::with(['settings_values' => function ($query) use ($site_id)
    {   $query->where('site_id', '=', $site_id );
    }, 'parent'])->orderBy('parent_id')->get();

    $configObj = array();
    // $configObj['uncategorized'] = array();
    foreach ($siteSettings as $s) {
        //make config
        if (!$s->is_category) {
            $conf = new \stdClass();
            $conf->id = $s->id;
            $conf->name = $s->name;
            $conf->title = $s->title;
            $conf->type = $s->type;
            $conf->order = $s->order;
            $conf->desc = $s->description;
            $conf->value = $s->settings_values->pluck('value')->first();
            $conf->data_type = $s->settings_values->pluck('data_type')->first();
            $conf->parent_id = $s->parent_id;

            $configObj[$conf->name] = $conf;
        }
    }
return $configObj;

}

function getConfig($site_id) {

    //all settings for site id function ($query) use ($activated) {
    $siteSettings = Setting::with(['settings_values' => function ($query) use ($site_id)
    {   $query->where('site_id', '=', $site_id );
    }, 'parent'])->orderBy('parent_id')->get();

    $configObj = array();
   // $configObj['uncategorized'] = array();

    foreach ($siteSettings as $s)
    {
        //make config
        if ($s->is_category)
        {
            $cat = new \stdClass();
            $cat->name =$s->name;
            $cat->id = $s->id;
            $cat->title = $s->title;
            $cat->desc = $s->description;
            $cat->order = $s->order;
            $cat->childs=[];
            $cat->items = array();

            $configObj[$s->name] = $cat;

            /* THIS ADDS HIERARCHY TO CATEGORIES

            if(!is_null($s->parent_id)){
                foreach($configObj as $catTitle => $objCat){
                    if( $objCat->id == $s->parent_id){
                        $configObj[$catTitle]->childs[$s->name] = $cat;
                    }
                }
            }else
                $configObj[$s->name] = $cat;
             */
        }
        else
        {
            $conf = new \stdClass();
            $conf->id = $s->id;
            $conf->name = $s->name;
            $conf->title = $s->title;
            $conf->type = $s->type;
            $conf->order = $s->order;
            $conf->desc = $s->description;
            $conf->value = $s->settings_values->pluck('value')->first();
            $conf->data_type = $s->settings_values->pluck('data_type')->first();

            $conf->parent_id = $s->parent_id;

            if (isset($configObj[$s->parent->name])) {
                $configObj[$s->parent->name]->items[$conf->name] = $conf;
            } else {
                echo 'uncategorized';
            }
        }

    }

    session(['siteConfig' => (object) $configObj]);
    return (object) $configObj;

}

function arraySearch($needle, $haystack){

    $return = false;
    foreach($haystack as $key => $val){
        var_dump($key);
        var_dump($val);
        if($needle == $key){
            var_dump('FIN');
            return $val;
        }
        else if(is_array($val) || is_object($val)){
            if( count((array)$val) > 1){
                var_dump($val);
                $return = arraySearch($needle, $val);

                /*foreach($val as $eIdx => $element){
                    if($needle === $key){
                        return $val;
                    }else if(is_array($element)){
                    var_dump($eIdx);
                    var_dump($element);
                   // dd($key,$val);


                    }
                }*/
            }
          return false;
           // dd($key,$val);
           // $return = arraySearch($needle, $val);
        }
    }
    return $return;
}

