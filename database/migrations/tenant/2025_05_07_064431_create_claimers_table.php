<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('claimers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lost_and_found_id')->constrained('lost_and_founds')->onDelete('cascade');
            $table->string('name');
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('claimers');
    }
};
