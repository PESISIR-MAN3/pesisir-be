<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationMethod extends Model
{
    protected $table = 'donation_methods';

    protected $fillable = [
        'method_name',
        'account_number',
        'owner_name'
    ];

    public function donations(){
        return $this->hasMany(Donation::class);
    }
}
