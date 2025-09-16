<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';

    protected $fillable = [
        'reporter_name',
        'reporter_email',
        'reporter_address',
        'reporter_phone',
        'photo_path',
        'location_id',
    ];

    public function location() {
        return $this->hasOne(Location::class);
    }
}
