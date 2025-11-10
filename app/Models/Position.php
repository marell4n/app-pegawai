<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    protected $fillable = [
        'nama_jabatan',
        'gaji_pokok',
    ];
    
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'jabatan_id');
    }
}
