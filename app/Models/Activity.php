<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activities';
    
    protected $fillable = [
        'activity_name',
        'activity_desc',
        'activity_date',
        'location_id',
    ];

    public function location() {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function volunteers() {
        return $this->hasMany(Volunteer::class);
    }
}
