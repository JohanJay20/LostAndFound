<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('tenant_requests', function (Blueprint $table) {
        $table->id();
        $table->string('username');
        $table->string('organization');
        $table->string('domain');
        $table->string('address');
        $table->string('email');
        $table->string('plan')->nullable();
        $table->enum('status', ['active', 'pending', 'rejected', 'disabled'])->default('pending');
        $table->json('data')->nullable();
        $table->timestamps();
    });
}
    /**
   * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_requests');
    }
};
