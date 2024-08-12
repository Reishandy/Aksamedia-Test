<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // TUGAS 3
    public function get(Request $request)
    {
        $query = Employee::query();

        // Filters
        if ($request->query('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->query('division_id')) {
            $query->where('division_id', $request->division_id);
        }

        // Pagination can be set here (per page)
        $per_page = $request->query('per_page') ? $request->query('per_page') : 10; // Not specified but...
        $employees = $query->paginate($per_page);

        // Formatting the response to match the expected requirements output
        $formattedEmployees = $employees->map(function ($employee) {
            return [
                'id' => $employee->id,
                'image' => $employee->image,
                'name' => $employee->name,
                'phone' => $employee->phone,
                'division' => $employee->division,
                'position' => $employee->position,  // Harus dibawah for some reason
            ];
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Employees retrieved, amounted to ' . $employees->total() . ' employees',
            'data' => [
                'employees' => $formattedEmployees,
            ],
            'pagination' => [
                'current_page' => $employees->currentPage(),
                'last_page' => $employees->lastPage(),
                'per_page' => $employees->perPage(),
                'total' => $employees->total(),
            ],
        ]);
    }

    // TUGAS 4
    public function post(Request $request)
    {
        // The store method
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'division_id' => 'required|uuid|exists:divisions,id',
            'position' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // max 2MB
        ]);

        $employee = Employee::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'division_id' => $validated['division_id'],
            'position' => $validated['position'],
            'image' => $request->file('image')->store('employee_images', 'public') ?? null,
        ]);

        return response()->json([
                'status' => 'success',
                'message' => 'Employee created successfully']
        );
    }

    // TUGAS 5
    public function put(Request $request, $id)
    {
        // PUT workarounds laravel limitations
        // please use method spoofing for PUT requests
        if (empty($request->all())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Laravel limitations',
                'details' => 'Please use method spoofing for PUT requests, change the request method to POST and add _method=PUT in the request body',
                'received_request_body' => $request->all(),
            ], 400);
        }

        // The update method
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'division_id' => 'required|uuid|exists:divisions,id',
            'position' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(['status' => 'error', 'message' => 'Employee not found'], 404);
        }

        $employee->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'division_id' => $validated['division_id'],
            'position' => $validated['position'],
            'image' => $request->file('image') ? $request->file('image')->store('employee_images', 'public') : $employee->image,
        ]);

        return response()->json(['status' => 'success',
                'message' => 'Employee updated successfully']
        );
    }

    // TUGAS 6
    public function delete($id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json(['status' => 'error', 'message' => 'Employee not found'], 404);
        }

        $employee->delete();

        return response()->json([
            'status' => 'success', 'message' => 'Employee deleted successfully'
        ]);
    }
}
