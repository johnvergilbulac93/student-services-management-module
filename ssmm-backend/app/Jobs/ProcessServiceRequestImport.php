<?php

namespace App\Jobs;

use App\Models\ImportLog;
use App\Models\Student;
use App\Models\StudentServiceRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ProcessServiceRequestImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $userId;
    protected $filename;

    public function __construct($filePath, $userId, $filename)
    {
        $this->filePath = $filePath;
        $this->userId = $userId;
        $this->filename = $filename;
    }

    public function handle()
    {
        $rows = Excel::toCollection(null, $this->filePath)[0]; // Only first sheet

        $summary = [
            'total_rows' => $rows->count(),
            'successful_requests' => 0,
            'new_students' => 0,
            'skipped_rows' => [],
        ];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 for header row offset
            $studentNumber = trim($row['student_number'] ?? '');
            $serviceType = strtolower(trim($row['service_type'] ?? ''));
            $dateRequested = $row['requested_date'] ?? null;

            // --- Validation ---
            if (empty($studentNumber)) {
                $summary['skipped_rows'][] = [
                    'row' => $rowNumber,
                    'reason' => 'Missing Student Number'
                ];
                continue;
            }

            $student = Student::where('student_number', $studentNumber)->first();

            // --- Auto-create Student if not found ---
            if (!$student) {
                $student = Student::create([
                    'student_number' => $studentNumber,
                    'first_name' => $row['first_name'] ?? 'Imported',
                    'last_name' => $row['last_name'] ?? 'Student',
                    'is_imported' => true,
                    'status' => 'Active',
                ]);
                $summary['new_students']++;
            } elseif ($student->status !== 'Active') {
                $summary['skipped_rows'][] = [
                    'row' => $rowNumber,
                    'reason' => 'Student is Inactive'
                ];
                continue;
            }

            // --- Service Type Normalization ---
            $serviceType = $this->normalizeServiceType($serviceType);

            if (!$serviceType) {
                $summary['skipped_rows'][] = [
                    'row' => $rowNumber,
                    'reason' => 'Invalid Service Type'
                ];
                continue;
            }

            // --- Duplicate Check ---
            if (StudentServiceRequest::where('student_id', $student->id)
                ->where('service_type', $serviceType)
                ->where('date_requested', $dateRequested)
                ->exists()
            ) {
                $summary['skipped_rows'][] = [
                    'row' => $rowNumber,
                    'reason' => 'Duplicate Service Request'
                ];
                continue;
            }

            // --- Create Service Request ---
            StudentServiceRequest::create([
                'student_id' => $student->id,
                'service_type' => $serviceType,
                'date_requested' => $dateRequested,
                'remarks' => $row['remarks'] ?? null,
            ]);

            $summary['successful_requests']++;
        }

        // --- Save Import Log ---
        ImportLog::create([
            'filename' => $this->filename,
            'user_id' => $this->userId,
            'summary_json' => json_encode($summary),
        ]);
    }

    private function normalizeServiceType($type)
    {
        $map = [
            'good moral certificate' => ['goodmoral', 'good moral', 'good moral cert', 'good moral certificate'],
            'id replacement' => ['id', 'id repl', 'id replace', 'id replacement'],
            'form 137' => ['form 137', 'form137', 'form-137'],
        ];

        foreach ($map as $valid => $aliases) {
            if (in_array($type, $aliases)) return $valid;
        }

        return null;
    }
}
