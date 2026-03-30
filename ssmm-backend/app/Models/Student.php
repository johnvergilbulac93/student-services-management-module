<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;


#[Fillable(['student_number', 'first_name', 'last_name', 'grade_level', 'email', 'status', 'is_imported'])]

class Student extends Model
{
    use SoftDeletes;

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
