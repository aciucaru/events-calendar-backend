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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id_fk')->nullable()
                    ->references('id')->on('meeting_appointments')
                    ->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('guest_user_id_fk')->nullable()
                    ->references('id')->on('users')
                    ->cascadeOnUpdate()->nullOnDelete();
            $table->enum('guest_answer', ['NO_ANSWER', 'YES', 'NO', 'MAYBE']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
