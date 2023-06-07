<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'branch_id', 'status', 'created_by', 'updated_by'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
