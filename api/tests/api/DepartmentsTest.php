<?php

namespace api;

use TestCase;

class DepartmentsTest extends TestCase
{
    const TEST_DEP_NAME = 'Test Dep4';
    const CHANGE_TO_DEP_NAME = 'Test Dep1 Changed';

    public function testGetList() {
        $response = $this->json('GET', '/api/departments')
            ->seeStatusCode(200);

        $response->response->decodeResponseJson()->assertCount(3);

        $response
            ->seeJsonContains(['id' => 'uuid1', 'name' => 'Test Dep1'])
            ->seeJsonContains(['id' => 'uuid2', 'name' => 'Test Dep2'])
            ->seeJsonContains(['id' => 'uuid3', 'name' => 'Test Dep3']);
    }

    public function testGetDepartment() {
        $response = $this->json('GET', '/api/departments/uuid1')
            ->seeStatusCode(200)
            ->seeJsonStructure(['id', 'name', 'employees'])
            ->seeJsonContains(['id' => 'uuid1', 'name' => 'Test Dep1']);

        $employees = $response->response->json('employees');
        $this->assertCount(5, $employees);

        foreach($employees as $employee) {
            $this->seeJsonStructure(['id', 'name', 'salary', 'department_id'], $employee);
        }
    }

    public function testGetEmptyDepartment() {
        $response = $this->json('GET', '/api/departments/uuid3')
            ->seeStatusCode(200)
            ->seeJsonStructure(['id', 'name', 'employees'])
            ->seeJsonContains(['id' => 'uuid3', 'name' => 'Test Dep3']);

        $employees = $response->response->json('employees');
        $this->assertCount(0, $employees);
    }

    public function testGetNonExistedDepartment() {
        $this->json('GET', '/api/departments/uuid4')
            ->seeStatusCode(404);
    }

    public function testAddDepartment() {
        $response = $this->json('POST', '/api/departments/create', [
            'name' => self::TEST_DEP_NAME
        ])
            ->seeStatusCode(200)
            ->seeJsonStructure(['id']);

        $id = $response->response->decodeResponseJson()->json('id');

        $this->seeInDatabase('departments', [
            'id' => $id,
            'name' => self::TEST_DEP_NAME
        ]);
    }

    public function testAddEmptyDepartment()
    {
        $this->json('POST', '/api/departments/create', [])
            ->seeStatusCode(400)
            ->seeJson(['message' => 'The given data was invalid.']);
    }

    public function testUpdateDepartment() {
        $this->seeInDatabase('departments', [
            'id' => 'uuid1',
            'name' => 'Test Dep1'
        ]);

        $this->json('POST', '/api/departments/uuid1', [
            'name' => self::CHANGE_TO_DEP_NAME
        ])
            ->seeStatusCode(200)
            ->seeJson(['id' => 'uuid1']);

        $this->seeInDatabase('departments', [
            'id' => 'uuid1',
            'name' => self::CHANGE_TO_DEP_NAME
        ]);
    }

    public function testUpdateDepartmentInvalidData() {
        $this->seeInDatabase('departments', [
            'id' => 'uuid1',
            'name' => 'Test Dep1'
        ]);

        $this->json('POST', '/api/departments/uuid1', [])
            ->seeStatusCode(400)
            ->seeJson(['message' => 'The given data was invalid.']);

        $this->seeInDatabase('departments', [
            'id' => 'uuid1',
            'name' => 'Test Dep1'
        ]);
    }

    public function testUpdateNonExistedDepartment() {
        $this->notSeeInDatabase('departments', [
            'id' => 'uuid4',
            'name' => 'Test Dep3'
        ]);

        $this->json('POST', '/api/departments/uuid4', [
            'name' => self::CHANGE_TO_DEP_NAME
        ])
            ->seeStatusCode(404)
            ->seeJson(['message' => 'Item not found.']);

        $this->notSeeInDatabase('departments', [
            'id' => 'uuid4',
            'name' => 'Test Dep3'
        ]);
    }

    public function testDeleteEmptyDepartment() {
        $response = $this->json('POST', '/api/departments/create', [
            'name' => self::TEST_DEP_NAME
        ])
            ->seeStatusCode(200)
            ->seeJsonStructure(['id']);

        $id = $response->response->json('id');

        $this->seeInDatabase('departments', [
            'id' => $id,
            'name' => self::TEST_DEP_NAME
        ]);

        $this->json('DELETE', "/api/departments/$id")
            ->seeStatusCode(200)
            ->seeJson(['id' => $id]);

        $this->notSeeInDatabase('departments', [
            'id' => $id,
            'name' => self::TEST_DEP_NAME
        ]);
    }

    public function testDeleteNotEmptyDepartment() {
        $response = $this->json('POST', '/api/departments/create', [
            'name' => self::TEST_DEP_NAME
        ])
            ->seeStatusCode(200)
            ->seeJsonStructure(['id']);

        $id = $response->response->json('id');

        $this->seeInDatabase('departments', [
            'id' => $id,
            'name' => self::TEST_DEP_NAME
        ]);

        $this->json('POST', '/api/employees/create', [
            'name' => 'Test Guy 3',
            'salary' => 10000.0,
            'department_id' => $id
        ])
            ->seeStatusCode(200);

        $this->seeInDatabase('employees', [
            'department_id' => $id
        ]);

        $this->json('DELETE', "/api/departments/$id")
            ->seeStatusCode(200)
            ->seeJson(['id' => $id]);

        $this->notSeeInDatabase('departments', [
            'id' => $id,
            'name' => self::TEST_DEP_NAME
        ]);

        $this->notSeeInDatabase('employees', [
            'department_id' => $id
        ]);
    }

    public function testDeleteNotExistedDepartment() {
        $this->notSeeInDatabase('departments', [
            'id' => 'uuid4',
            'name' => self::TEST_DEP_NAME
        ]);

        $this->json('DELETE', '/api/departments/uuid4')
            ->seeStatusCode(404)
            ->seeJson(['message' => 'Item not found.']);
    }
}
