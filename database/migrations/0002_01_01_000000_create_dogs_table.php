<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shelter_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('breed')->nullable();
            $table->enum('size', ['S', 'M', 'L', 'XL'])->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->string('color')->nullable();
            $table->enum('sex', ['male', 'female', 'unknown'])->default('unknown');
            $table->date('birth_date')->nullable();
            $table->date('intake_date');
            $table->enum('intake_source', ['rescue', 'surrender', 'other'])->default('other');
            $table->string('kennel')->nullable();
            $table->enum('quarantine_status', ['clear', 'quarantine'])->default('clear');
            $table->enum('adoption_status', ['available', 'pending', 'adopted', 'not_ready'])->default('not_ready');
            $table->decimal('adoption_fee', 10, 2)->default(0);
            $table->text('story')->nullable();
            $table->text('temperament')->nullable();
            $table->boolean('good_with_kids')->default(false);
            $table->boolean('good_with_pets')->default(false);
            $table->string('photo_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dogs');
    }
};
