<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'customer_id',
        'name',
        'type',
        'email',
        'phone',
        'address',
        'meter_reading',
        'total_consumption',
        'status',
    ];

    public function waterUsages(): HasMany
    {
        return $this->hasMany(WaterUsage::class);
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }
}
