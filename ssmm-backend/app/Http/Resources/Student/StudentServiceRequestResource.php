<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentServiceRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student' => new StudentResource($this->whenLoaded('student')),
            'service_type' => $this->service_type,
            'date_requested' => $this->date_requested,
            'status' => $this->status,
            'remarks' => $this->remarks,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
