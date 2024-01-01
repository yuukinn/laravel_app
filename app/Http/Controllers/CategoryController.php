<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ExpenseCategory;
use App\Models\ExpenseCategoryUser;
use App\Http\Requests\CategoryPostRequest;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;


class CategoryController extends Controller
{
    //
    public function create(): View
    {
        $categories = ExpenseCategory::all();

        $currentDate = Carbon::now();
        $currentDateFormatted = $currentDate->format('Y-m-d');

        $user = Auth::user();
        $categories = $user->expenseCategory;
        return view('expense/create', [
            'categories' => $categories,
            'user' => $user,
            'currentDateFormatted' => $currentDateFormatted,
        ]);     
    }

    public function store(CategoryPostRequest $request):RedirectResponse
    {   
        //カテゴリ登録用のオブジェクトを用意
        $expensecategory = new ExpenseCategory();
        

        
        //リクエストオブジェクトからパラメータを取得
        $expensecategory -> category = $request -> category;
        // var_dump($expensecategory);
        // exit;        

        $existenceCategory = ExpenseCategory::where('category', $request->category)->get();
        $number = count($existenceCategory);
        if (count($existenceCategory)){
            $categoryId = $existenceCategory->first()->id;
        }

       
        // 中間テーブルの重複チェック
        $existingEntry = ExpenseCategoryUser::where([
            'user_id' => $request->user_id,
            'expense_category_id' => $categoryId,
        ])
        ->first();
        if($existingEntry){
            return redirect(route('expense.create'))
            ->with('message', 'すでに追加しています。');
        }
        
        // カテゴリが存在している場合
        if ($existenceCategory) {
                //カテゴリユーザーテーブル(中間テーブル)を登録
                ExpenseCategoryUser::create([
                    'user_id' => $request->user_id,
                    'expense_category_id' => $categoryId,
                ]);

        } else {
            DB::transaction(function() use($expensecategory, $request) {
                //保存
                $expensecategory -> save();

                //カテゴリユーザーテーブル(中間テーブル)を登録
                $expensecategory->users()->attach($request->user_id);
            });
        }

        //登録完了後詳細ページへリダイレクト
        return redirect(route('expense.index'))
            ->with('message', 'カテゴリを追加しました');

    }
}
