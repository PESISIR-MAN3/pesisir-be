<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $table = 'donations';

    protected $fillable = [
        'donation_amount',
        'image_slip',
        'donation_method_id'
    ];

    public function donation_method(){
        return $this->belongsTo(DonationMethod::class, 'donation_method_id');
    }
}
