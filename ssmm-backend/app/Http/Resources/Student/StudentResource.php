<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'student_number' => $this->student_number,
            'first_name'     => $this->first_name,
            'last_name'      => $this->last_name,
            'full_name'      => $this->first_name . ' ' . $this->last_name,
            'grade_level'    => $this->grade_level,
            'email'          => $this->email,
            'is_imported'    => $this->is_imported,
            'status'         => $this->status ? 'Active' : 'Inactive',
            'created_at'     => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
