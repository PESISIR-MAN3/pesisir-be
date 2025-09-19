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
        'report_desc',
        'report_date',
        'image_path',
        'location_id',
    ];

    public function location() {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
