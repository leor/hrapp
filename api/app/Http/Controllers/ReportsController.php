<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{

    /**
     * Returns all departments along with the highest salary within each department.
     *
     * A department with no employees should show 0 as the highest salary.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function highestSalary(): \Illuminate\Http\JsonResponse
    {
        $result = DB::select("SELECT d.id, d.name, coalesce((SELECT MAX(salary) FROM employees WHERE department_id = d.id), 0) as max_salary, (SELECT count(*) FROM employees WHERE department_id = d.id) as employee_count FROM departments d ORDER BY max_salary DESC");

        return response()->json($result);
    }

    /**
     * Returns just those departments that have more than two employees that earn over 50k.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function twoWith50(): \Illuminate\Http\JsonResponse
    {
        // in MySQL 8 we could use WITH instruction to preselect RICH (salary >= 50000) employees, but we use MySQL 5
        $result = DB::select("SELECT d.id, d.name, (SELECT count(*) FROM employees WHERE department_id = d.id) as employee_count, (SELECT count(*) FROM employees WHERE department_id = d.id AND salary >= 50000) as rich_count FROM departments d WHERE (SELECT count(*) FROM employees WHERE department_id = d.id AND salary >= 50000) >= 2");

        return response()->json($result);
    }

}
