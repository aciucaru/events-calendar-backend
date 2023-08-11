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
        Schema::create('meeting_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id_fk')->nullable()
                    ->references('id')->on('meeting_events')
                    ->cascadeOnUpdate()->nullOnDelete();
            $table->boolean('active')->default(0);
            $table->dateTime('start');
            $table->dateTime('end');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_appointments');
    }
};
