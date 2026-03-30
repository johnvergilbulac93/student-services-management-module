<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\Student\StudentServiceRequestResource;
use App\Imports\Student\StudentServiceRequestImport;
use App\Models\StudentServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class ServiceRequestController extends Controller
{

    // List all service requests with filters
    public function index(Request $request)
    {
        $query = StudentServiceRequest::with('student');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date_requested', [$request->start_date, $request->end_date]);
        }
        $res = $query->orderBy('date_requested', 'desc')->paginate(10);
        return StudentServiceRequestResource::collection($res);
    }
    // Create a new request
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'service_type' => ['required', Rule::in(['ID Replacement', 'Good Moral Certificate', 'Form 137'])],
            'date_requested' => 'required|date',
            'remarks' => 'nullable|string',
        ]);
        $serviceRequest = StudentServiceRequest::create($validated);

        // return response()->json($serviceRequest, 201);
        return Response::apiSuccess(new StudentServiceRequestResource($serviceRequest), 'Service request created successfully', 201);
    }
    // Show a specific request
    public function show($id)
    {
        $res = StudentServiceRequest::with('student')->findOrFail($id);
        return  new StudentServiceRequestResource($res);
    }
    // Staff approves or rejects a request
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['Approved', 'Rejected'])],
        ]);

        $serviceRequest = StudentServiceRequest::findOrFail($id);
        $serviceRequest->update($validated);

        return Response::apiSuccess(new StudentServiceRequestResource($serviceRequest), 'Service request status updated successfully');
    }

    // Admin deletes a request
    public function destroy($id)
    {
        $serviceRequest = StudentServiceRequest::findOrFail($id);
        $serviceRequest->delete();
        return Response::apiSuccess(null, 'Service request deleted successfully');
    }
    // Upload service requests via Excel
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('file');
        $path = $file->store('imports');

        Excel::queueImport(new StudentServiceRequestImport(auth()->id(), $file->getClientOriginalName()), $path);

        return Response::apiSuccess(null, 'File uploaded successfully. Processing will be done in the background.');
    }
}
