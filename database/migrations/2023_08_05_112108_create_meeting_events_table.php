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
        Schema::create('meeting_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('host_user_id_fk')->nullable()
                    ->references('id')->on('users')
                    ->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('location_id_fk')->nullable()
                    ->references('id')->on('locations')
                    ->cascadeOnUpdate()->nullOnDelete();
            $table->string('title');
            $table->string('description', 4096);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_events');
    }
};
