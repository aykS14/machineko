<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscoveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discoveries', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->uuid('uuid')->unique();
            $table->bigInteger('user_id');
            $table->string('pattern');
            $table->string('locate');
            $table->double('Lat');
            $table->double('Lng');
            
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
        Schema::dropIfExists('discoveries');
    }
}
