<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    /** @use HasFactory<\Database\Factories\CalificationFactory> */
    use HasFactory;

    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
