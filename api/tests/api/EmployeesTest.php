<?php

namespace api;

use TestCase;

class EmployeesTest extends TestCase
{
    const TEST_EMPLOYEE_NAME = 'Test Guy1';
    const TEST_UP_EMPLOYEE_NAME = 'Frier Guy';

    public function testInsertEmployee() {
        $response = $this->json('POST', '/api/employees/create', [
            'name' => self::TEST_EMPLOYEE_NAME,
            'salary' => 20000.0,
            'department_id' => 'uuid1'
        ])
            ->seeStatusCode(200)
            ->seeJsonStructure(['id']);

        $id = $response->response->json('id');

        $this->seeInDatabase('employees', [
            'id' => $id,
            'department_id' => 'uuid1',
            'name' => self::TEST_EMPLOYEE_NAME,
            'salary' => 20000.0,
        ]);
    }

    public function testInsertInvalidEmployee() {
        $this->json('POST', '/api/employees/create', [
            'department_id' => 'uuid1',
            'salary' => 20000.0,
        ])
            ->seeStatusCode(400)
            ->seeJson(['message' => 'The given data was invalid.']);

        $this->json('POST', '/api/employees/create', [
            'name' => self::TEST_EMPLOYEE_NAME,
            'department_id' => 'uuid1'
        ])
            ->seeStatusCode(400)
            ->seeJson(['message' => 'The given data was invalid.']);

        $this->json('POST', '/api/employees/create', [
            'name' => self::TEST_EMPLOYEE_NAME,
            'salary' => 'lol',
            'department_id' => 'uuid1'
        ])
            ->seeStatusCode(400)
            ->seeJson(['message' => 'The given data was invalid.']);

        $this->json('POST', '/api/employees/create', [
            'name' => self::TEST_EMPLOYEE_NAME,
            'salary' => 20000.0,
        ])
            ->seeStatusCode(400)
            ->seeJson(['message' => 'The given data was invalid.']);

    }

    public function testInsertEmployeeToNonExistsDepartment() {
         $this->json('POST', '/api/employees/create', [
             'name' => self::TEST_EMPLOYEE_NAME,
             'salary' => 20000.0,
             'department_id' => 'uuid4'
        ])
            ->seeStatusCode(404)
            ->seeJson(['message' => 'Department not found.']);

        $this->notSeeInDatabase('employees', [
            'department_id' => 'uuid4',
            'name' => self::TEST_EMPLOYEE_NAME,
            'salary' => 20000.0,
        ]);
    }

    public function testUpdateEmployee() {
        $this->seeInDatabase('employees', [
            'id' => 'eid1',
            'department_id' => 'uuid1',
            'name' => 'Free Guy',
            'salary' => 0,
        ]);

        $this->json('POST', '/api/employees/eid1', [
            'name' => self::TEST_UP_EMPLOYEE_NAME,
            'salary' => 10000.0,
            'department_id' => 'uuid2'
        ])
            ->seeStatusCode(200)
            ->seeJson(['id' => 'eid1']);

        $this->seeInDatabase('employees', [
            'id' => 'eid1',
            'department_id' => 'uuid2',
            'name' => self::TEST_UP_EMPLOYEE_NAME,
            'salary' => 10000.0,
        ]);
    }

    public function testUpdateEmployeeInvalidName()
    {
        $this->seeInDatabase('employees', [
            'id' => 'eid1',
            'department_id' => 'uuid1',
            'name' => 'Free Guy',
            'salary' => 0,
        ]);

        $this->json('POST', '/api/employees/eid1', [
            'salary' => 10000.0,
            'department_id' => 'uuid1'
        ])
            ->seeStatusCode(400)
            ->seeJson(['message' => 'The given data was invalid.']);
    }

    public function testUpdateEmployeeInvalidSalary()
    {
        $this->seeInDatabase('employees', [
            'id' => 'eid1',
            'department_id' => 'uuid1',
            'name' => 'Free Guy',
            'salary' => 0,
        ]);

        $this->json('POST', '/api/employees/eid1', [
            'name' => self::TEST_UP_EMPLOYEE_NAME,
            'department_id' => 'uuid1'
        ])
            ->seeStatusCode(400)
            ->seeJson(['message' => 'The given data was invalid.']);

        $this->json('POST', '/api/employees/eid1', [
            'name' => self::TEST_UP_EMPLOYEE_NAME,
            'department_id' => 'uuid1',
            'salary' => 'lol',
        ])
            ->seeStatusCode(400)
            ->seeJson(['message' => 'The given data was invalid.']);
    }

    public function testUpdateEmployeeInvalidDepartment()
    {
        $this->seeInDatabase('employees', [
            'id' => 'eid1',
            'department_id' => 'uuid1',
            'name' => 'Free Guy',
            'salary' => 0,
        ]);

        $this->json('POST', '/api/employees/eid1', [
            'name' => self::TEST_UP_EMPLOYEE_NAME,
            'salary' => 10000.0,
        ])
            ->seeStatusCode(400)
            ->seeJson(['message' => 'The given data was invalid.']);

        $this->notSeeInDatabase('departments', [
            'id' => 'uuid5',
        ]);

        $this->json('POST', '/api/employees/eid1', [
            'name' => self::TEST_UP_EMPLOYEE_NAME,
            'salary' => 10000.0,
            'department_id' => 'uuid5'
        ])
            ->seeStatusCode(404)
            ->seeJson(['message' => 'Department not found.']);
    }

    public function testUpdateNotExistedEmployee()
    {
        $this->notSeeInDatabase('employees', [
            'id' => 'eid4',
        ]);

        $this->json('POST', '/api/employees/eid4', [
            'name' => self::TEST_UP_EMPLOYEE_NAME,
            'salary' => 10000.0,
            'department_id' => 'uuid1'
        ])
            ->seeStatusCode(404)
            ->seeJson(['message' => 'Employee not found.']);
    }

    public function testDeleteEmployee()
    {
        $response = $this->json('POST', '/api/employees/create', [
            'name' => self::TEST_EMPLOYEE_NAME,
            'salary' => 20000.0,
            'department_id' => 'uuid1'
        ])
            ->seeStatusCode(200)
            ->seeJsonStructure(['id']);

        $id = $response->response->json('id');

        $this->seeInDatabase('employees', [
            'id' => $id,
            'department_id' => 'uuid1',
            'name' => self::TEST_EMPLOYEE_NAME,
            'salary' => 20000.0,
        ]);

        $this->json('DELETE', "/api/employees/$id")
            ->seeStatusCode(200)
            ->seeJson(['id' => $id]);

        $this->notSeeInDatabase('employees', [
            'id' => $id,
        ]);
    }

    public function testDeleteNotExistedEmployee()
    {
        $this->notSeeInDatabase('employees', [
            'id' => 'eid5',
        ]);

        $this->json('DELETE', "/api/employees/eid5")
            ->seeStatusCode(404)
            ->seeJson(['message' => 'Employee not found.']);
    }
}
