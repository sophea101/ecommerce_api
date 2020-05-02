<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_locations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->dateTime('lat');
            $table->dateTime('long');
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
        Schema::dropIfExists('favorite');
        Schema::dropIfExists('deliver_locations');
        Schema::dropIfExists('delivery_locations');
    }
}
