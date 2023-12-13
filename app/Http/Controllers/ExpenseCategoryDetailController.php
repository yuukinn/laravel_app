<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\View\View;
use App\Http\Requests\ExpensePostRequest;
use App\Events\ExpenseRegistered;
use Illuminate\Support\Facades\Auth;
use App\Models\ExpenseCategoryDetail;

use Illuminate\Http\RedirectResponse;

class ExpenseCategoryDetailController extends Controller
{
    public function getCategoryDetails($type=null):View
    {
        // 資産計算用:初期値
        $investmentSum = 0;
        $consumptionSum = 0;
        $wasteSum = 0;

        $user = Auth::user();
        $userID = $user->id;
        //カテゴリ詳細一覧を取得
        switch($type) {
            case "inv.":
                $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
                    ->where([
                        ['user_id', $userID],
                        ['is_investment', '=', 1]
                    ])
                    ->get();

                // データの件数を取得
                $totalCount = $categoryDetails->count();
                break;
            
            case 'cons.';
                $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
                    ->where([
                        ['user_id', $userID],
                        ['is_consumption', '=', 1]
                    ])
                    ->get();

                // データの件数を取得
                $totalCount = $categoryDetails->count();
                break;

            case 'waste';
                $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
                    ->where([
                        ['user_id', $userID],
                        ['is_waste', '=', 1]
                    ])
                    ->get();

                // データの件数を取得
                $totalCount = $categoryDetails->count();
                break;
            
            default:
                $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
                ->where('user_id', $userID)->get();
                // データの件数を取得
                $totalCount = $categoryDetails->count();
        }

        // 資産別計算処理
        foreach ($categoryDetails as $categoryDetail) {
            if ($categoryDetail->is_investment) {
                $investmentSum = $investmentSum + $categoryDetail->amount;    
            } else if ($categoryDetail->is_consumption) {
                $consumptionSum = $consumptionSum + $categoryDetail->amount; 
            } else {
                $wasteSum = $wasteSum + $categoryDetail->amount;
            }
        }

        return view('expense/index', [
            'categoryDetails' => $categoryDetails,
            'investmentSum' => $investmentSum,
            'consumptionSum' => $consumptionSum,
            'wasteSum' => $wasteSum,
            'userID'=> $userID
        ]);
    }

    //　支出詳細登録
    public function storeDetail(ExpensePostRequest $request): RedirectResponse
    {
        $user = Auth::user();
        //Category詳細登録用のオブジェクトを作成する
        $categoryDetail = new ExpenseCategoryDetail();

        //リクエストオブジェクトからパラメータ取得
        $categoryDetail->user_id = $request->user_id;
        $categoryDetail->category_id = $request->category_id;
        $categoryDetail->category_detail = $request->category_detail;
        $categoryDetail->amount = $request->price;
        $categoryDetail->date = $request->date;
        $categoryDetail->is_investment = $request->asset_type === '投資';
        $categoryDetail->is_consumption = $request->asset_type === '消費';
        $categoryDetail->is_waste = $request->asset_type === '浪費';
    
        //保存
        $categoryDetail->save();

        if(!$user->has_set_email){
            // イベントハッカ
            event(new ExpenseRegistered($categoryDetail));
        }

        return redirect(route('expense.index'));

    }

    public function destroy(ExpenseCategoryDetail $expenseCategoryDetail):RedirectResponse {
        //削除
        $expenseCategoryDetail->delete();
        //削除したら一覧画面にリダイレクト
        return redirect(route('expense.index'))->with('message', '支出項目を削除しました。');
    }

    public function exportCsv()
    {
        //HTTPヘッダーの設定
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment;'
        ];
        
        $user = Auth::user();
        $userID = $user->id;
        $type = request('asset_type');

        //ファイルの名前作成
        $fileName = Carbon::now()->format('YmdHis').'expense_list';

        $callBack = function () use($type, $userID)
        {   
            //ストリームを作成してファイルに書き込みができるようにする
            $stream = fopen('php://output', 'w');

            //ヘッダー行の定義
            $head = [
                'カテゴリ名',
                '詳細',
                '金額',
                '資産タイプ',
                '日付'
            ];

            //文字化け対策
            mb_convert_variables('SJIS', 'UTF-8', $head);
            //ヘッダー書き込み
            fputcsv($stream, $head);       

            //カテゴリ詳細一覧を取得
            switch($type) {
                case "inv.";
                    $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
                        ->where([
                            ['user_id', $userID],
                            ['is_investment', '=', 1]
                        ])
                        ->orderBy('date', 'asc');

                    break;
            
                case 'cons.';
                    $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
                        ->where([
                            ['user_id', $userID],
                            ['is_consumption', '=', 1]
                        ])
                       ->orderBy('date', 'asc');

                    break;
            
                case 'waste';
                    $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
                        ->where([
                            ['user_id', $userID],
                            ['is_waste', '=', 1]
                        ])
                        ->orderBy('date', 'asc');

                    break;
            
                default:
                    $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
                        ->where('user_id', $userID)
                        ->orderBy('date', 'asc');
            }

            foreach ($categoryDetails->cursor() as $categoryDetail) {
                $data = [
                    $categoryDetail->expenseCategory->category,
                    $categoryDetail->category_detail,
                    $categoryDetail->amount,
                ];   

                // 資産タイプの条件分岐
                if ($categoryDetail->is_investment) {
                    $data[] = '投資';
                } elseif ($categoryDetail->is_consumption) {
                    $data[] = '消費';
                } else {
                    $data[] = '浪費';
                }

                $data[] =  $categoryDetail->date;
                //文字化け対策
                mb_convert_variables('SJIS', 'UTF-8', $data);
                fputcsv($stream, $data);
            }
            fclose($stream);
        };

        return response()->streamDownload($callBack, $fileName, $headers);
    }
    
}
