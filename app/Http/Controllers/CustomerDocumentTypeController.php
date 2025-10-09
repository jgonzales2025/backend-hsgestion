<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\CustomerDocumentType;

class CustomerDocumentTypeController extends Controller
{
    public function indexForDrivers(): JsonResponse
    {
        $driverDocumentType = CustomerDocumentType::where('st_driver', 1)->get();
        return response()->json($driverDocumentType);
    }
}
