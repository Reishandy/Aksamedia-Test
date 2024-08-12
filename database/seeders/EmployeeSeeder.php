<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $divisions = Division::all();

        foreach ($divisions as $division) {
            for ($i = 1; $i <= 5; $i++) {
                Employee::create([
                    'id' => Str::uuid(),
                    'name' => "Employee $i in $division->name",
                    'phone' => '081234567890',
                    'image' => null,
                    'division_id' => $division->id,
                    'position' => 'Position ' . $i,
                ]);
            }
        }
    }
}
