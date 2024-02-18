<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\IncomeRecord;

class IncomeController extends Controller
{
    // 収入追加
    function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        // 支出登録用オブジェクトを作成する
        $income = new IncomeRecord();

        // リクエストオブジェクトからパラメータを取得
        $income->user_id = $user->id;
        $income->category = $request->category;
        $income->amount = $request->price;
        $income->date = $request->date;

        // 保存
        $income->save();

        return redirect(route('expense.create'))->with('message', '収入を追加しました。');
    }
}
