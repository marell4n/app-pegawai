<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceReview extends Model
{
    protected $fillable = [
        'karyawan_id',
        'tanggal_review',
        'skor',
        'catatan_feedback',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'karyawan_id');
    }
}
