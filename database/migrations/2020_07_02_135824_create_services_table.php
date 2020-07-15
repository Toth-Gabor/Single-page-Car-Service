<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            // ügyfél egyedi azonosítója
            $table->bigInteger('client_id')->unsigned();
            $table->integer('car_id')->unsigned();
            $table->increments('lognumber')->unsigned();
            $table->foreign(['client_id', 'car_id'])
                ->references(['client_id', 'car_id'])
                ->on('cars')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // szerviz alkalom (ügyfél és autójaként egyedi)
            $table->primary(['client_id', 'car_id', 'lognumber']);

            // esemény típusa
            $table->string('event')->nullable();
            // esemény időpontja
            $table->dateTime('eventtime')->nullable();
            // munkanlap azonosítója
            $table->integer('document_id')->default(0);
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
        Schema::dropIfExists('services');
    }
}
