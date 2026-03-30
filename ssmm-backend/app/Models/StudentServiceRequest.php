<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['student_id', 'service_type', 'date_requested', 'status', 'remarks'])]
class StudentServiceRequest extends Model
{
    use SoftDeletes;
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
