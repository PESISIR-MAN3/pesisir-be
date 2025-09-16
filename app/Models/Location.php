<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Prompts\Table;

class Location extends Model
{
    protected $table = 'locations';
    
    protected $fillable = [
        'location_name',
        'location_address',
        'latitude',
        'longitude'
    ];
    
    public function activities() {
        return $this->hasOne(Activity::class);
    }
    public function reports() {
        return $this->hasMany(Report::class);
    }
}
