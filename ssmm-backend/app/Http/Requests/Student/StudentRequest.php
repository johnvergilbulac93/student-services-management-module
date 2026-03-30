<?php

namespace App\Http\Requests\Student;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $studentId = $this->route('student');
        return [
            'student_number' => 'required|string|max:50|unique:students,student_number,' . $studentId,
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'grade_level'    => 'required|string|max:50',
            'email'          => 'required|email|unique:students,email,' . $studentId,
            'status' => 'required|boolean',
        ];
    }
}
