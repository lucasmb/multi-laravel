<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SettingValue extends Model
{

    protected $table = 'settings_values';
    protected $fillable = ['setting_id', 'site_id', 'data_type', 'value'];

    public function settings()
    {
        return $this->belongsTo('App\Models\Setting');
    }
}
