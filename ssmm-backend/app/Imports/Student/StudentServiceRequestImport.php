<?php

namespace App\Imports\Student;

use App\Events\Import\NotificationImportEvent;
use App\Models\ImportLog;
use App\Models\Student;
use App\Models\StudentServiceRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\ImportFailed;

class StudentServiceRequestImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue, WithEvents
{
    protected $userId;
    protected $filename;
    protected $summary;
    public function registerEvents(): array
    {
        return [
            AfterImport::class =>  function () {
                broadcast(new NotificationImportEvent('Success', 'Import Completed.', 1));
            },
            ImportFailed::class => function (ImportFailed $event) {
                broadcast(new NotificationImportEvent('Import failed', $event->getException()->getMessage(), 0));
            },

        ];
    }
    public function __construct($userId, $filename)
    {
        $this->userId = $userId;
        $this->filename = $filename;

        $this->summary = [
            'total_rows' => 0,
            'successful_requests' => 0,
            'new_students' => 0,
            'skipped_rows' => [],
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $this->summary['total_rows']++;
            $rowNumber = $index + 2; // header offset

            $studentNumber = trim($row['student_number'] ?? '');
            $serviceTypeRaw = strtolower(trim($row['service_type'] ?? ''));
            $dateRequested = $row['requested_date'] ?? null;
            if (is_numeric($dateRequested)) {
                $dateRequested = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateRequested)->format('Y-m-d');
            } else {
                $dateRequested = date('Y-m-d', strtotime($dateRequested));
            }
            $firstName = $row['first_name'] ?? 'Imported';
            $lastName = $row['last_name'] ?? 'Student';

            // --- Validation: Missing Student Number ---
            if (empty($studentNumber)) {
                $this->logSkipped($rowNumber, 'Missing Student Number');
                continue;
            }

            // --- Find or Create Student ---
            $student = Student::where('student_number', $studentNumber)->first();

            if (!$student) {
                $student = Student::create([
                    'student_number' => $studentNumber,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'status' => 1,
                    'is_imported' => 1,
                ]);
                $this->summary['new_students']++;
            } elseif ($student->status == 0) {
                $this->logSkipped($rowNumber, 'Student is Inactive');
                continue;
            }

            // --- Service Type Normalization ---
            $serviceType = $this->normalizeServiceType($serviceTypeRaw);
            if (!$serviceType) {
                $this->logSkipped($rowNumber, 'Invalid Service Type');
                continue;
            }

            // --- Duplicate Check ---
            if (StudentServiceRequest::where('student_id', $student->id)
                ->where('service_type', $serviceType)
                ->where('date_requested', $dateRequested)
                ->exists()
            ) {
                $this->logSkipped($rowNumber, 'Duplicate Service Request');
                continue;
            }

            // --- Create Service Request ---
            StudentServiceRequest::create([
                'student_id' => $student->id,
                'service_type' => $serviceType,
                'date_requested' => $dateRequested,
                'remarks' => $row['remarks'] ?? null,
            ]);

            $this->summary['successful_requests']++;
        }

        // --- Save Import Log ---
        ImportLog::create([
            'filename' => $this->filename,
            'user_id' => $this->userId,
            'summary_json' => json_encode($this->summary),
        ]);
    }

    private function logSkipped($rowNumber, $reason)
    {
        $this->summary['skipped_rows'][] = [
            'row' => $rowNumber,
            'reason' => $reason,
        ];
    }

    private function normalizeServiceType($type)
    {
        $map = [
            'Good Moral Certificate' => ['goodmoral', 'good moral', 'good moral cert', 'good moral certificate'],
            'ID Replacement' => ['id', 'id repl', 'id replace', 'id replacement'],
            'Form 137' => ['form 137', 'form137', 'form-137'],
        ];

        foreach ($map as $valid => $aliases) {
            if (in_array($type, $aliases)) return $valid;
        }

        return null;
    }

    public function chunkSize(): int
    {
        return 100; // process 100 rows per chunk
    }
}
