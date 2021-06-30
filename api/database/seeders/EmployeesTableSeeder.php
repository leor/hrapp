<?php

namespace Database\Seeders;

use Faker\Provider\Person;
use Faker\Provider\Uuid;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        // certain persons for tests
        DB::insert("INSERT INTO `employees` (`id`, `department_id`, `name`, `salary`) VALUES ('eid1', 'uuid1', 'Free Guy', 0)");
        DB::insert("INSERT INTO `employees` (`id`, `department_id`, `name`, `salary`) VALUES ('eid2', 'uuid1', 'Paid Guy', 70000.0)");
        DB::insert("INSERT INTO `employees` (`id`, `department_id`, `name`, `salary`) VALUES ('eid3', 'uuid2', 'Paid Guy', 60000.0)");

        // More people
        for($i = 0; $i < 3; $i++) {
            DB::table('employees')->insert([
                'id' => Uuid::uuid(),
                'department_id' => 'uuid1',
                'name' => Person::firstNameMale(),
                'salary' => $i === 1 ? 50000 : mt_rand(5000, 49000)
            ]);
        }

        for($i = 0; $i < 2; $i++) {
            DB::table('employees')->insert([
                'id' => Uuid::uuid(),
                'department_id' => 'uuid2',
                'name' => Person::firstNameMale(),
                'salary' => mt_rand(5000, 49000)
            ]);
        }

        DB::commit();
    }
}
