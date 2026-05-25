<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dog_id')->constrained()->cascadeOnDelete();
            $table->foreignId('caretaker_id')->constrained('users');
            $table->enum('contact_type', ['feeding', 'handling', 'bathing', 'medication_application', 'walking', 'cleaning_cage', 'other']);
            $table->integer('duration_minutes')->nullable();
            $table->enum('ppe_used', ['gloves', 'mask', 'full_suit', 'none'])->default('none');
            $table->text('notes')->nullable();
            $table->timestamp('logged_at');
            $table->timestamps();

            $table->index(['dog_id', 'logged_at']);
            $table->index(['caretaker_id', 'logged_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_logs');
    }
};
