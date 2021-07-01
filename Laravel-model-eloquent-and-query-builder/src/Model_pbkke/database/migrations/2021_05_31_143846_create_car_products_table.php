<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_product', function (Blueprint $table) {
            // tambah id sendiri
            $table->integer('car_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->foreign('car_id')
                ->references('id')
                ->on('cars')
                ->onDelete('cascade');     
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');                
        });


        // 
        // Schema::create(table:'project_user', function (Blueprint $table)) {
        //      syntax foreignID berasal dari laravel7
        //     $table->foreignID(column:'project_id')->constrained();
        //     $table->foreignID(column:'user_id')->constrained();

        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('car_products');
    }
}
