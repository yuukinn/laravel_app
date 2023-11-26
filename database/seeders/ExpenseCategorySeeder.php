<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;
use App\Models\ExpenseCategoryDetail;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = [
            [ 'category' => '食費' ],
            [ 'category' => '外食' ],
            [ 'category' => '日用品' ],
            [ 'category' => '交通費' ],
            [ 'category' => '衣服' ],
            [ 'category' => '趣味娯楽' ],
            [ 'category' => 'インフラ' ],
            [ 'category' => '自己投資' ],
            [ 'category' => 'サブスク' ]
        ];

        //登録処理
        foreach ($categories as $category) {
            ExpenseCategory::create($category);
        }

        ExpenseCategoryDetail::factory(5)->create();

    }
}
