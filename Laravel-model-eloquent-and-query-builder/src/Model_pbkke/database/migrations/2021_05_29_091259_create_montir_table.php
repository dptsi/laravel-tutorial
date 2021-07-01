<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMontirTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('montir', function (Blueprint $table) {
            $table->increments('id_montir');
            $table->string('name_montir');
            $table->timestamps();
        });

        // Schema::create('car_models', function (Blueprint $table){
        //     $table->increments('id');
        //     $table->unsignedInteger('car_id'); // foreignkey
        //     $table->string('model_name');
        //     $table->timestamps();        
        //     $table->foreign('car_id')
        //           ->references('id')
        //           ->on('cars')
        //           ->onDelete('cascade');
        //         //   ->onDelete('set null');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('montir');
    }
}
