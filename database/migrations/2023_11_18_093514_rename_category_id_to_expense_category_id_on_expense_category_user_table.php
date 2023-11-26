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
        Schema::table('expense_category_user', function (Blueprint $table) {
            //
            $table->renameColumn('category_id', 'expense_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expense_category_user', function (Blueprint $table) {
            //
            $table->renameColumn('expense_category_id', 'category_id');
        });
    }
};
