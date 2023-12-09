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
        Schema::table('goal_amounts', function (Blueprint $table) {
            //
            $table->date('goal_date')->default('2023-12-31');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goal_amounts', function (Blueprint $table) {
            //
            $table->date('goal_date')->default('2023-12-31');
        });
    }
};
