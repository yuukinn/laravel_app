<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ExpenseCategory;

class UserExpenseCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = User::all();
        $expenseCategories = ExpenseCategory::all();

        foreach ($expenseCategories as $expenseCategory) {
            $userIds = $users
                ->pluck('id')
                ->all();

            $expenseCategory->users()->attach($userIds);
        }
    }
}
