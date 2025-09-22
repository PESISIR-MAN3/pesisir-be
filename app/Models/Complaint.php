<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table = 'complaints';

    protected $fillable = [
        'complainant_name',
        'complainant_email',
        'complainant_address',
        'complainant_phone',
        'complaint_desc',
        'actual_date',
        'image_path',
        'location_id',
    ];

    public function location() {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
