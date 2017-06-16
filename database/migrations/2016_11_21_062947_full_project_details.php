<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FullProjectDetails extends Migration
{
    public function up()
    {
        Schema::create('project_details', function (Blueprint $table) {
            $table->increments('id');
            $table->date('overtime_end_date')->nullable();
            $table->integer('project_details_id');
            $table->string('project_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('time_allocation_status')->default(0);
            $table->integer('overtime_final_status')->default(0);
            $table->string('remarks')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }

   
    public function down()
    {
        Schema::drop('project_details');
    }
}
