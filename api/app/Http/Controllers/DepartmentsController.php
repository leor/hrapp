<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class DepartmentsController extends Controller
{
    /**
     * Returns a list of possible Departments
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $result = DB::select("SELECT `id`, `name` FROM `departments`");

        return response()->json(array_values($result));
    }

    /**
     * Returns Department data (including employees list)
     *
     * @param string $id Department Id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(string $id): \Illuminate\Http\JsonResponse
    {
        $status = 200;
        $result = DB::selectOne("SELECT `id`, `name` FROM `departments` WHERE `id` = ?", [
            $id
        ]);

        if($result) {
            $result->employees = DB::select("SELECT `id`, `name`, `salary`, `department_id` FROM `employees` WHERE `department_id` = ?", [
                $id
            ]);
        } else {
            $status = 404;
        }

        return response()->json($result ?? [
            "message" => "Item not found"
        ], $status);
    }

    /**
     * Creates new Department from data
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
                'name' => 'required|string'
            ]);

            $id = Uuid::uuid1();

            DB::insert("INSERT INTO `departments` (`id`, `name`) VALUES (?, ?)", [
                $id, $request->input('name')
            ]);

            $result = [
                'id' => $id
            ];
        } catch(\Exception $e) {
            $result = [
                'message' => $e->getMessage()
            ];
            $status = 400;
        }

        return response()->json($result, $status);
    }

    /**
     * Updates certain Department with data
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
                'name' => 'required|string'
            ]);

            $count = DB::update("UPDATE `departments` SET `name` = ? WHERE id = ?", [
                $request->input('name'), $id
            ]);

            if($count > 0) {
                $result = [
                    'id' => $id
                ];
            } else {
                $result = [
                    'message' => 'Item not found.'
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
     * Removes certain Department (with the list of Employees)
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
            $count = DB::update("DELETE FROM `departments` WHERE id = ?", [
                $id
            ]);

            if($count > 0) {
                $result = [
                    'id' => $id
                ];
            } else {
                $result = [
                    'message' => 'Item not found.'
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
}
