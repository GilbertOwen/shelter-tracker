<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dogs', function (Blueprint $table) {
            // Filter publik & admin: shelter + status adopsi + aktif
            $table->index(['shelter_id', 'is_active'], 'dogs_shelter_active_idx');
            $table->index(['shelter_id', 'adoption_status', 'is_active'], 'dogs_shelter_adoption_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            // Workload caretaker & daftar user per shelter
            $table->index(['shelter_id', 'role'], 'users_shelter_role_idx');
        });
    }

    public function down(): void
    {
        Schema::table('dogs', function (Blueprint $table) {
            $table->dropIndex('dogs_shelter_active_idx');
            $table->dropIndex('dogs_shelter_adoption_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_shelter_role_idx');
        });
    }
};
