<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaterUsage extends Model
{
    use SoftDeletes;

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
