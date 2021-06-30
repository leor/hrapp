<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EmployeesController extends Controller
{
    /**
     * Creates new Employee from data
     *
     * Returns new item Id
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $status = 200;

        try {
            $this->validate($request, [
                'name' => 'required|string',
                'salary' => 'required|numeric',
                'department_id' => 'required'
            ]);

            $departmentId = $request->input('department_id');

            $this->checkDepartment($departmentId);

            $id = Uuid::uuid1();

            DB::insert("INSERT INTO `employees` (`id`, `department_id`, `name`, `salary`) VALUES (?, ?, ?, ?)", [
                $id,
                $departmentId,
                $request->input('name'),
                $request->input('salary')
            ]);

            $result = [
                'id' => $id
            ];
        } catch(NotFoundHttpException $e) {
            $result = [
                'message' => $e->getMessage()
            ];
            $status = 404;
        } catch(\Exception $e) {
            $result = [
                'message' => $e->getMessage()
            ];
            $status = 400;
        }

        return response()->json($result, $status);
    }

    /**
     * Updates certain Employee with data
     *
     * Returns item Id
     *
     * @param string $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(string $id, Request $request): \Illuminate\Http\JsonResponse
    {
        $status = 200;

        try {
            $this->validate($request, [
                'name' => 'required|string',
                'salary' => 'required|numeric',
                'department_id' => 'required'
            ]);

            $departmentId = $request->input('department_id');

            $this->checkDepartment($departmentId);

            $employeeCount = DB::update("UPDATE `employees` SET `department_id` = ?, `name` = ?, `salary` = ? WHERE id = ?", [
                $departmentId,
                $request->input('name'),
                $request->input('salary'),
                $id,
            ]);

            if($employeeCount > 0) {
                $result = [
                    'id' => $id
                ];
            } else {
                throw new NotFoundHttpException('Employee not found.');
            }
        } catch(NotFoundHttpException $e) {
            $result = [
                'message' => $e->getMessage()
            ];
            $status = 404;
        } catch(\Exception $e) {
            $result = [
                'message' => $e->getMessage()
            ];
            $status = 400;
        }

        return response()->json($result, $status);
    }

    /**
     * Removes certain Employee
     *
     * Returns item Id
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(string $id): \Illuminate\Http\JsonResponse
    {
        $status = 200;

        try {
            $count = DB::update("DELETE FROM `employees` WHERE id = ?", [
                $id
            ]);

            if($count > 0) {
                $result = [
                    'id' => $id
                ];
            } else {
                $result = [
                    'message' => 'Employee not found.'
                ];
                $status = 404;
            }
        } catch(\Exception $e) {
            $result = [
                'message' => $e->getMessage()
            ];
            $status = 400;
        }
        return response()->json($result, $status);
    }

    /**
     * Checks if Department exists in DB
     *
     * @param string $departmentId
     */
    private function checkDepartment(string $departmentId) {
        $departmentCount = DB::selectOne(
            "SELECT count(*) as count FROM `departments` WHERE `id` = ?",
            [
                $departmentId
            ]);
        if ($departmentCount->count === 0) {
            throw new NotFoundHttpException('Department not found.');
        }
    }
}
