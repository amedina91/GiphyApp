<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiInteractionsTable extends Migration
{
    public function up()
    {
        Schema::create('api_interactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('service');
            $table->text('request_body')->nullable();
            $table->integer('response_code');
            $table->text('response_body')->nullable();
            $table->ipAddress('ip_address');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_interactions');
    }
}
