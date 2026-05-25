<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dog_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to')->constrained('users');
            $table->enum('type', ['feeding', 'bathing', 'medication', 'vaccination', 'vet_visit', 'exercise', 'grooming', 'behavior_training']);
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->integer('duration_minutes')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['pending', 'completed', 'overdue'])->default('pending');
            $table->timestamps();

            $table->index(['assigned_to', 'start_time']);
            $table->index(['dog_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
