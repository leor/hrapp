<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Employees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function(Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->unsignedFloat('salary')->default(0);

            $table->foreignUuid('department_id')
                ->references('id')
                ->on('departments')
                ->onDelete('cascade');

            $table->index(['department_id', 'salary']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
