<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    /** @use HasFactory<\Database\Factories\HomeworkFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'course_student_id',
    ];

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_student_id');
    }
    public function student()
    {
        return $this->belongsTo(CourseStudent::class, 'course_student_id');
    }

}
