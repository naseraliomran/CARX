<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->integer("idUser");
            $table->integer("companyID");
            $table->integer("state");
            $table->text("name");
            $table->text("describe");
            $table->integer("manufacturingYear");
            $table->double("price");
            $table->text("color");
            $table->text("city");
            $table->double("mileage");
            $table->bigInteger("engineCapacity");
            $table->string("model");
            $table->text("motionVector");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
