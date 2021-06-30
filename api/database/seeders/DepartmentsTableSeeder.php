<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        DB::table('departments')->insert([
            'id' => 'uuid1',
            'name' => 'Test Dep1'
        ]);
        DB::table('departments')->insert([
            'id' => 'uuid2',
            'name' => 'Test Dep2'
        ]);
        DB::table('departments')->insert([
            'id' => 'uuid3',
            'name' => 'Test Dep3'
        ]);
        DB::commit();
    }
}
