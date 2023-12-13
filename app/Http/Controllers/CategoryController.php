<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ExpenseCategory;
use App\Models\ExpenseCategoryUser;


class CategoryController extends Controller
{
    //
    public function create(): View
    {
        $categories = ExpenseCategory::all();
        
        $user = Auth::user();
        $categories = $user->expenseCategory;
        return view('expense/create', [
            'categories' => $categories,
            'user' => $user,
        ]);     
    }

    public function store(Request $request):RedirectResponse
    {   
        $user = Auth::user();
        //カテゴリ登録用のオブジェクトを用意
        $expensecategory = new ExpenseCategory();
        
        //リクエストオブジェクトからパラメータを取得
        $expensecategory -> category = $request -> category;

        $existenceCategory = ExpenseCategory::where('category', $request->category)->get();
        $categoryId = $existenceCategory->first()->id;

        if ($existenceCategory) {
                //カテゴリユーザーテーブル(中間テーブル)を登録
                ExpenseCategoryUser::create([
                    'user_id' => $user->id,
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
