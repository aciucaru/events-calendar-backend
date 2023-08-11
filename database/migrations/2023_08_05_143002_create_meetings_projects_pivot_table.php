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
        Schema::create('meetings_projects_pivot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id_fk')->nullable()
                    ->references('id')->on('meeting_events')
                    ->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('project_id_fk')->nullable()
                    ->references('id')->on('projects')
                    ->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings_projects_pivot');
    }
};
