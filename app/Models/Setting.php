<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{

    protected $fillable = [
        'name', 'title', 'description', 'type', 'is_category', 'parent_id'
    ];

    public function children() {
        return $this->hasMany('App\Models\Setting','parent_id');
    }
    public function parent() {
        return $this->belongsTo('App\Models\Setting','parent_id');
    }

    public function settings_values()
    {
        return $this->hasMany('App\Models\SettingValue');
    }


}
