<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function get(Request $request)
    {
        $query = Division::query();

        // Filter by name as per requirement
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Pagination can be set here (per page) if provided
        $page = $request->has('page') ? $request->page : 10;
        $divisions = $query->paginate($page);

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
