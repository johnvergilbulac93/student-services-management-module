<?php

namespace App\Http\Controllers\Api\Logs;

use App\Http\Controllers\Controller;
use App\Http\Resources\Logs\LogsResource;
use App\Models\ImportLog;
use Illuminate\Support\Facades\Response;

class LogsController extends Controller
{
    public function index()
    {
        $search = request('search'); 

        $logs = ImportLog::with('user')
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%"); 
                });
            })
            ->latest()
            ->get();
        return Response::apiSuccess(LogsResource::collection($logs), 'Logs retrieved successfully', 200);
    }
}
