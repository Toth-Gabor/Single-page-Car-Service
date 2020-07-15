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
            // ügyfél egyedi azonosítója
            $table->bigInteger('client_id')->unsigned();
            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            // ügyfél autójának azonosítója (ügyfelenként egyedi)
            $table->increments('car_id');
            $table->primary(['client_id', 'car_id']);
            // autó típusa
            $table->string('type');
            // regisztrálás időpontja
            $table->dateTime('registered')->useCurrent();
            // értéke 1 ha saját márkás, értéke 0 ha nem saját márkás
            $table->boolean('ownbrand');
            // balesetek száma
            $table->integer('accident');
            $table->engine = 'MyISAM';
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
