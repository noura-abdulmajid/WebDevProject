<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageVisitsTable extends Migration
{
    public function up()
    {
        Schema::create('page_visits', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->string('page');
            $table->timestamp('timestamp');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('page_visits');
    }
}