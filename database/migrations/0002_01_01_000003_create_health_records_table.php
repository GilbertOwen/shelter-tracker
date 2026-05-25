<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dog_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recorded_by')->constrained('users');
            $table->text('observation');
            $table->enum('severity', ['normal', 'watch', 'concerning', 'urgent']);
            $table->text('symptoms')->nullable();
            $table->boolean('zoonosis_flag')->default(false);
            $table->string('photo_url')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('recorded_at');
            $table->timestamps();

            $table->index(['dog_id', 'recorded_at']);
            $table->index(['severity', 'zoonosis_flag']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_records');
    }
};
