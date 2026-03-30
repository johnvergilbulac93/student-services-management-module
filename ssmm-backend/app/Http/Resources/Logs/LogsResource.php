<?php

namespace App\Http\Resources\Logs;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogsResource extends JsonResource
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
            'filename' => $this->filename,
            'uploaded_by' => $this->user ? $this->user->name : 'Unknown',
            'summary_logs' => json_decode($this->summary_json, true),
            'date_uploaded' => $this->created_at->toDateTimeString(),
        ];
    }
}
