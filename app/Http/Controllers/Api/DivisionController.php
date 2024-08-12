<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    // TUGAS 2
    public function get(Request $request)
    {
        $query = Division::query();

        // Filter by name as per requirement
        if ($request->query('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Pagination can be set here (per page)
        $per_page = $request->query('per_page') ? $request->query('per_page') : 10; // Not specified but...
        $divisions = $query->paginate($per_page);

        return response()->json([
            'test' => $request->name,
            'status' => 'success',
            'message' => 'Divisions retrieved, amounted to ' . $divisions->total() . ' divisions',
            'data' => [
                'divisions' => $divisions->items(),
            ],
            'pagination' => [
                'current_page' => $divisions->currentPage(),
                'last_page' => $divisions->lastPage(),
                'per_page' => $divisions->perPage(),
                'total' => $divisions->total(),
            ],
        ]);
    }
}
