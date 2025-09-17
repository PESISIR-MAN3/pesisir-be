<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Volunteer extends Model
{
    protected $table = 'volunteers';

    protected $fillable = [
        'volunteer_name',
        'volunteer_email',
        'volunteer_address',
        'volunteer_phone',
        'volunteer_gender',
        'reason_desc',
        'image_slip',
        'activity_id'
    ];

    public function activities(){
        return $this->belongsTo(Activity::class);
    }
}
