<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationMethod extends Model
{
    protected $table = 'donation_mehtods';

    protected $fillable = [
        'method_name',
        'account_number',
        'owner_name'
    ];

    public function donation(){
        return $this->hasMany(Donation::class);
    }
}
