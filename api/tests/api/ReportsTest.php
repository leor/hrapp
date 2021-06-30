<?php

namespace api;

use Illuminate\Support\Facades\DB;
use TestCase;

class ReportsTest extends TestCase
{
    public function testReportsHighestSalary() {
        $response = $this
            ->json('GET', '/api/reports/highest-salary')
            ->seeStatusCode(200);

        $response->response->assertJsonCount(3);

        $response
            ->seeJsonContains(['id' => 'uuid1', 'name' => 'Test Dep1', 'max_salary' => 70000.0, 'employee_count' => 5])
            ->seeJsonContains(['id' => 'uuid2', 'name' => 'Test Dep2', 'max_salary' => 60000.0, 'employee_count' => 3])
            ->seeJsonContains(['id' => 'uuid3', 'name' => 'Test Dep3', 'max_salary' => 0, 'employee_count' => 0]);

        $data = $response->response->json();

        foreach($data as $item) {
            $first = DB::selectOne("SELECT coalesce(max(salary), 0) as max_salary FROM employees WHERE department_id = ?", [$item['id']]);
            $this->assertEquals($first->max_salary, $item['max_salary']);
        }
    }

    public function testReportsTwoWith50() {
        $response = $this
            ->json('GET', '/api/reports/two-with-50')
            ->seeStatusCode(200);

        $response->response->assertJsonCount(1);

        $response
            ->seeJsonContains(['id' => 'uuid1', 'name' => 'Test Dep1', 'employee_count' => 5, 'rich_count' => 2]);

        // we know those values for test DB data provided by EmployeesTableSeeder
        $depsEmployeesCounts = [
            'uuid1' => 2,
            'uuid2' => 1,
            'uuid3' => 0
        ];

        foreach($depsEmployeesCounts as $departmentId => $count) {
            $data = DB::selectOne("SELECT count(*) as `count` FROM `employees` WHERE `department_id` = ? AND salary >= 50000", [ $departmentId ]);
            $this->assertEquals($count, $data->count);
        }
    }
}
