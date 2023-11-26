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
        Schema::create('user_expenseCategory', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained();
            $table->foreignId('category_id')
                ->references('id')
                ->on('expense_categories') // ここを修正
                ->constrained('expense_categories');
            $table->timestamps();
            $table->primary(['user_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_expenseCategory');
    }
};


