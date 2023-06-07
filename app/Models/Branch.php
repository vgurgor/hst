<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'campus_id', 'status', 'created_by', 'updated_by'];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }
}
