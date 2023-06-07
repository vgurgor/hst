<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'major_id',
        'grade_id',
        'name',
        'status',
        'weekly_frequency',
        'created_by',
        'updated_by',
    ];

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
