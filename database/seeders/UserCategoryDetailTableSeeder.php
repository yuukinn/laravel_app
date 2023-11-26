<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ExpenseCategoryDetail;

class UserCategoryDetailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $expenseCategoryDetails = ExpenseCategoryDetail::all();

        foreach ($expenseCategoryDetails as $expenseCategoryDetail) {
            $userIds = $users
               ->pluck('id')
               ->all();

            $expenseCategoryDetails->users()->attach($userIds);
        }
    }
}
