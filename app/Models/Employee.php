<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'nama_lengkap',
        'email',
        'nomor_telepon',
        'tanggal_lahir',
        'alamat',
        'tanggal_masuk',
        'department_id',
        'jabatan_id',
        'status',
    ];

    public function departments(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_employee', 'employee_id', 'department_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'jabatan_id');
    }

    public function attendance(): HasMany
    {
       return $this->hasMany(Attendance::class, 'karyawan_id');
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class, 'karyawan_id');
    }
}
