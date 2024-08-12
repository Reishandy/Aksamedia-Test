<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'image',
        'division_id',
        'position',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'division_id',
    ];

    protected $casts = [
        'id' => 'string',
        'division_id' => 'string',
    ];

    protected $with = [
        'division'
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($employee) {
            if (empty($employee->id)) {
                $employee->id = (string)Str::uuid();
            }
        });
    }
}
