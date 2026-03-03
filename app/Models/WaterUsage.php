<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaterUsage extends Model
{
    protected $fillable = [
        'customer_id',
        'reading_date',
        'usage',
        'previous_reading',
        'current_reading',
    ];

    protected $casts = [
        'reading_date' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
