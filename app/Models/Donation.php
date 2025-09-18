<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $table = 'donations';

    protected $fillable = [
        'donation_method',
        'donation_amount',
        'image_slip',
    ];
}
