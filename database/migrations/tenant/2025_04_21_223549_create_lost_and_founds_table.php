<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLostAndFoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lost_and_founds', function (Blueprint $table) {
            $table->id();  // Primary key
            $table->string('item_name');  // Item name
            $table->text('description');  // Description of the item
            $table->timestamp('found_at');
            $table->string('status');  // Status of the item (found, claimed, unclaimed)
            $table->string('location');  // Location where the item was found
            $table->string('category');  // Date and time when the item was found
            $table->timestamps();  // Created at & Updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lost_and_founds');
    }
}
