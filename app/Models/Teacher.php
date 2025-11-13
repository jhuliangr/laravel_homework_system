<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    /** @use HasFactory<\Database\Factories\TeacherFactory> */
    use HasFactory;
    public function data()
    {
        return $this->belongsTo(User::class);
    }

    public function evaluations() 
    {
        return $this->hasMany(Evaluation::class);
    } 

    public function courses() 
    {
        return $this->hasMany(Courses::class);
    } 
}
