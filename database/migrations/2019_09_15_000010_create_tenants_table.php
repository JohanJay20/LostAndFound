<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('email');
            $table->string('organization');
            $table->string('domain')->unique(); // Ensure domain is unique
            $table->string('address');
            $table->string('plan')->default('Basic');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->json('data')->nullable();
            $table->json('customize_data')->nullable();
            $table->string('version')->default('v1.0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}
