<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\View\View;
use App\Http\Requests\ExpensePostRequest;
use App\Events\ExpenseRegistered;
use Illuminate\Support\Facades\Auth;
use App\Models\ExpenseCategoryDetail;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class ExpenseCategoryDetailController extends Controller
{
    public function getCategoryDetails($type=null):View|RedirectResponse
    {
        $date_type = "date_asc";
        $amount_type = "amount_asc";

        // var_dump($type);
        // exit;

        // 年月日を取得
        $currentDate = Carbon::now();
        $startOfMonth = $currentDate->copy()->startOfMonth()->format('Y-m-d');
        $endOfMonth = $currentDate->copy()->endOfMonth()->format('Y-m-d');
        $yearMonth = $currentDate->copy()->format('Y-m');
        
        // ログインユーザー
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
                    ->orderBy('created_at', 'desc')
                    ->paginate(4);

                // データの件数を取得
                // $totalCount = $categoryDetails->count();
                break;
            
            case 'cons.';
                $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
                    ->where([
                        ['user_id', $userID],
                        ['is_consumption', '=', 1]
                    ])
                    ->orderBy('created_at', 'desc')
                    ->paginate(4);

                // データの件数を取得
                // $totalCount = $categoryDetails->count();
                break;

            case 'waste';
                $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
                    ->where([
                        ['user_id', $userID],
                        ['is_waste', '=', 1]
                    ])
                    ->orderBy('created_at', 'desc')
                    ->paginate(4);

                // データの件数を取得
                // $totalCount = $categoryDetails->count();
                break;
            
            case 'date_asc';
                $date_type = "date_desc";
                $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
                    ->where([
                        ['user_id', $userID],
                    ])
                    ->whereBetween('date', [$startOfMonth, $endOfMonth])
                    ->orderBy('created_at', 'asc')
                    ->paginate(4);

                // データの件数を取得
                // $totalCount = $categoryDetails->count();
                break;

            case 'date_desc';
                // $date_type = "date_asc";
                $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
                    ->where([
                        ['user_id', $userID]
                    ])
                    ->whereBetween('date', [$startOfMonth, $endOfMonth])
                    ->orderBy('created_at', 'desc')
                    ->paginate(4);

                // データの件数を取得
                // $totalCount = $categoryDetails->count();
                break; 

            case 'amount_asc';
                // var_dump($type);
                // exit;
                $amount_type = "amount_desc";
                $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
                    ->where([
                        ['user_id', $userID],
                    ])
                    ->whereBetween('date', [$startOfMonth, $endOfMonth])// var_dump($startOfMonth . " " . $endOfMonth);
                    ->orderBy('amount', 'asc')
                    ->paginate(4);

                // データの件数を取得
                // $totalCount = $categoryDetails->count();
                break;

            case 'amount_desc';
                $amount_type = "amount_asc";
                // var_dump($amount_type);
                // exit;
                $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
                    ->where('user_id', $userID)
                    ->whereBetween('date', [$startOfMonth, $endOfMonth])
                    ->orderBy('amount', 'desc')
                    ->paginate(4);

                // データの件数を取得
                // $totalCount = $categoryDetails->count();
                break;
            
            default:
                $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
                ->where('user_id', $userID)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->orderBy('created_at', 'desc')
                ->paginate(4);
                // データの件数を取得
                // $totalCount = $categor>count();
        }

        $sum = ExpenseCategoryDetail::where('user_id', $userID)->sum('amount');

        // 日付
        // $query = ExpenseCategoryDetail::selectRaw("DISTINCT DATE_FORMAT(date, '%Y-%m') AS year_month");
        // dd($query->toSql());
        // exit;
        $year_month_expense_datas = ExpenseCategoryDetail::selectRaw("DISTINCT DATE_FORMAT(date, '%Y-%m') AS `year_month`")
            ->get();
       
        // // ページ番号
        // $page = request()->get('page', 1);
        // $MAX = 4;
        // $maxPage = ceil($totalCount/$MAX);
        // $validator = Validator::make(['page'=> $page], [
        //     'page' => 'nullable|integer|min:1|max:'. $maxPage,
        // ]);

        // // GETリクエストのバリデーション
        // if($validator->fails()){
        //     return redirect(route('expense.index'));
        // }

        // $slicedCategoryDetails = $categoryDetails->slice(($page - 1) * $MAX, $MAX);

        // 資産別計算処理
        // foreach ($categoryDetails as $categoryDetail) {
        //     $sum = $sum + $categoryDetail->amount;
            // if ($categoryDetail->is_investment) {
            //     $investmentSum = $investmentSum + $categoryDetail->amount;    
            // } else if ($categoryDetail->is_consumption) {
            //     $consumptionSum = $consumptionSum + $categoryDetail->amount; 
            // } else {
            //     $wasteSum = $wasteSum + $categoryDetail->amount;
            // }
        // }

        return view('expense/index', [
            'categoryDetails' => $categoryDetails,
            'sum' => $sum,
            'userID'=> $userID,
            // 'maxPage' => $maxPage,
            // 'page' => $page,
            'yearMonth' => $yearMonth,
            'year_month_expense_datas' => $year_month_expense_datas,
            'date_type' => $date_type,
            'amount_type' => $amount_type,
        ]);
    }

    //　支出詳細登録
    public function storeDetail(ExpensePostRequest $request): RedirectResponse
    {
        // 開始時間を取得
        $startTime = microtime(true);
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
        // 終了時間を取得
        $endTime = microtime(true);
 
        // // Log::info('デバッグ情報: ' . $debugInfo);
        // if(!$user->has_set_email){
        //     // イベントハッカ
        //     event(new ExpenseRegistered($categoryDetail));
        // }

        // 処理時間を計算（ミリ秒単位でログに記録）
        $executionTime = round(($endTime - $startTime) * 1000, 2);
        Log::info('処理時間: ' . $executionTime . 'ms');
        return redirect(route('expense.index'));

    }

    public function destroy(ExpenseCategoryDetail $expenseCategoryDetail):RedirectResponse {
        //削除
        $expenseCategoryDetail->delete();
        //削除したら一覧画面にリダイレクト
        return redirect(route('expense.index'))->with('message', '支出項目を削除しました。');
    }

    // レポート出力
    public function showReport():View
    {
         // 年月日を取得
        $currentDate = Carbon::now();
        $startOfMonth = $currentDate->copy()->startOfMonth()->format('Y-m-d');
        $endOfMonth = $currentDate->copy()->endOfMonth()->format('Y-m-d');
        $yearMonth = $currentDate->copy()->format('Y-m');

        // ログインユーザー
        $user = AUth::user();
        $userId = $user->id;

        $investmentSum = ExpenseCategoryDetail::where([
            ['user_id', $userId],
            ['is_investment', 1],
        ])
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');
        $consumptionSum = ExpenseCategoryDetail::where([
            ['user_id', $userId],
            ['is_consumption', 1],
        ])
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');
        $wasteSum = ExpenseCategoryDetail::where([
            ['user_id', $userId],
            ['is_waste', 1],
        ])
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');
        
        
        // $categoryTotals = ExpenseCategory::withSum('categoryDetails', 'amount')
        //     ->join('expense_category_details', 'expense_categories.id', '=', 'expense_category_details.category_id')
        //     ->where('expense_category_details.user_id', $userId)
        //     ->whereBetween('date', [$startOfMonth, $endOfMonth])
        //     ->groupBy('expense_categories.category')
        //     ->get();

        $categoryTotals = ExpenseCategory::withSum(['categoryDetails' => function ($query) use ($userId, $startOfMonth, $endOfMonth) {
            $query->where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth]);
        }], 'amount')
        ->get();

        // var_dump($categoryTotals[0]['category_details_sum_amount']);
        // exit;

        $categoryList = [];
        $amountList = [];

        foreach($categoryTotals as $categoryTotal) {
            array_push($categoryList, $categoryTotal['category']);
            array_push($amountList, $categoryTotal['category_details_sum_amount']);
        }

        return view('report/index', [
            'yearMonth' => $yearMonth,
            'investmentSum' => $investmentSum,
            'consumptionSum' => $consumptionSum,
            'wasteSum' => $wasteSum,
            'categoryList' => $categoryList,
            'amountList' => $amountList,
            'categoryTotals' => $categoryTotals,
        ]);
    }

    // public function exportCsv()
    // {
    //     //HTTPヘッダーの設定
    //     $headers = [
    //         'Content-Type' => 'text/csv',
    //         'Content-Disposition' => 'attachment;'
    //     ];
        
    //     $user = Auth::user();
    //     $userID = $user->id;
    //     $type = request('asset_type');

    //     //ファイルの名前作成
    //     $fileName = Carbon::now()->format('YmdHis').'expense_list.csv';

    //     $callBack = function () use($type, $userID)
    //     {   
    //         //ストリームを作成してファイルに書き込みができるようにする
    //         $stream = fopen('php://output', 'w');

    //         //ヘッダー行の定義
    //         $head = [
    //             'カテゴリ名',
    //             '詳細',
    //             '金額',
    //             '資産タイプ',
    //             '日付'
    //         ];

    //         //文字化け対策
    //         mb_convert_variables('SJIS', 'UTF-8', $head);
    //         //ヘッダー書き込み
    //         fputcsv($stream, $head);       

    //         //カテゴリ詳細一覧を取得
    //         switch($type) {
    //             case "inv.";
    //                 $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
    //                     ->where([
    //                         ['user_id', $userID],
    //                         ['is_investment', '=', 1]
    //                     ])
    //                     ->orderBy('date', 'asc');

    //                 break;
            
    //             case 'cons.';
    //                 $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
    //                     ->where([
    //                         ['user_id', $userID],
    //                         ['is_consumption', '=', 1]
    //                     ])
    //                    ->orderBy('date', 'asc');

    //                 break;
            
    //             case 'waste';
    //                 $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
    //                     ->where([
    //                         ['user_id', $userID],
    //                         ['is_waste', '=', 1]
    //                     ])
    //                     ->orderBy('date', 'asc');

    //                 break;
            
    //             default:
    //                 $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
    //                     ->where('user_id', $userID)
    //                     ->orderBy('date', 'asc');
    //         }

    //         foreach ($categoryDetails->cursor() as $categoryDetail) {
    //             $data = [
    //                 $categoryDetail->expenseCategory->category,
    //                 $categoryDetail->category_detail,
    //                 $categoryDetail->amount,
    //             ];   

    //             // 資産タイプの条件分岐
    //             if ($categoryDetail->is_investment) {
    //                 $data[] = '投資';
    //             } elseif ($categoryDetail->is_consumption) {
    //                 $data[] = '消費';
    //             } else {
    //                 $data[] = '浪費';
    //             }

    //             $data[] =  $categoryDetail->date;
    //             //文字化け対策
    //             mb_convert_variables('SJIS', 'UTF-8', $data);
    //             fputcsv($stream, $data);
    //         }
    //         fclose($stream);
    //     };

    //     return response()->streamDownload($callBack, $fileName, $headers);
    // }
    
}
