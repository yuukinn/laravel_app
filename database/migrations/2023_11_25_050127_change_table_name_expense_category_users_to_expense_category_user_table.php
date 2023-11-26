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
            //テーブル名を変更する処理
            Schema::rename('expense_category_users', 'expense_category_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expense_category_user', function (Blueprint $table) {
            //
        });
    }
};
