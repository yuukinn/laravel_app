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
use App\Models\IncomeRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ExpenseCategoryDetailController extends Controller
{
    public function getCategoryDetails($type=null,$year=null, $month=null, $targetMonth=null):View|RedirectResponse
    {
        // 並び替え定数
        $date_type = "date_asc";
        $amount_type = "amount_asc";
        

        // 年月日を取得
        $currentDate = Carbon::now();
        $startOfMonth = $currentDate->copy()->startOfMonth()->format('Y-m-d');
        $endOfMonth = $currentDate->copy()->endOfMonth()->format('Y-m-d');
        $yearMonth = $currentDate->copy()->format('Y-m');

        //　現在の年月取得
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;

        // 年月の設定
        if($year==null){
            $year = (string)$currentDate->year;
        }
        if($month==null){
            $month = (string)$currentDate->month;
        }

        // パラメーターで取得した値を設定
        $yearMonth = $year . '-' . $month;
        $startOfMonth = Carbon::createFromFormat('Y-m', $yearMonth)->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::createFromFormat('Y-m', $yearMonth)->endOfMonth()->format('Y-m-d');
        
        // 一月前の処理
        if($targetMonth == 'pre'){
            $month--;

            if($month < 1) {
                $year--;
                $month = 12;
            }
            $yearMonth = $year . '-' . $month;
            $startOfMonth = Carbon::createFromFormat('Y-m', $yearMonth)->startOfMonth()->format('Y-m-d');
            $endOfMonth = Carbon::createFromFormat('Y-m', $yearMonth)->endOfMonth()->format('Y-m-d');
        }

        // 一月先の処理
        if($targetMonth == 'next'){
            $month++;
            
            if($month > 12 ){
                $year++;
                $month = 1;
            }
            $yearMonth = $year . '-' . $month;
            $startOfMonth = Carbon::createFromFormat('Y-m', $yearMonth)->startOfMonth()->format('Y-m-d');
            $endOfMonth = Carbon::createFromFormat('Y-m', $yearMonth)->endOfMonth()->format('Y-m-d');
        }
        
        // ログインユーザー
        $user = Auth::user();
        $userID = $user->id;

        //カテゴリ詳細一覧を取得
        switch($type) {
            case 'date_asc';
                $date_type = "date_desc";
                $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
                    ->where([
                        ['user_id', $userID],
                    ])
                    ->whereBetween('date', [$startOfMonth, $endOfMonth])
                    ->orderBy('created_at', 'asc')
                    ->paginate(10);

                break;

            case 'date_desc';
                $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
                    ->where([
                        ['user_id', $userID]
                    ])
                    ->whereBetween('date', [$startOfMonth, $endOfMonth])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                break; 

            case 'amount_asc';
                $amount_type = "amount_desc";
                $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
                    ->where([
                        ['user_id', $userID],
                    ])
                    ->whereBetween('date', [$startOfMonth, $endOfMonth])// var_dump($startOfMonth . " " . $endOfMonth);
                    ->orderBy('amount', 'asc')
                    ->paginate(10);
                break;

            case 'amount_desc';
                $amount_type = "amount_asc";
                $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
                    ->where('user_id', $userID)
                    ->whereBetween('date', [$startOfMonth, $endOfMonth])
                    ->orderBy('amount', 'desc')
                    ->paginate(10);
                break;
            
            default:
                $categoryDetails = ExpenseCategoryDetail::with('expenseCategory')
                ->where('user_id', $userID)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->orderBy('date', 'desc')
                ->paginate(10);
        }

        // 支出合計処理
        $sum = ExpenseCategoryDetail::where('user_id', $userID)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('amount');
        
        // 収入合計処理
        $incomeSum = IncomeRecord::where('user_id', $userID)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('amount');

        // 収支
        $incomeAndExpense = $incomeSum - $sum;

        return view('expense/index', [
            'categoryDetails' => $categoryDetails,
            'sum' => $sum,
            'userID'=> $userID,
            'yearMonth' => $yearMonth,
            'date_type' => $date_type,
            'amount_type' => $amount_type,
            'currentYear' => $currentYear,
            'currentMonth'=> $currentMonth,
            'year' => (string)$year,
            'month' => (string)$month,
            'incomeSum' => $incomeSum,
            'incomeAndExpense' => $incomeAndExpense,
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
        
        // // Log::info('デバッグ情報: ' . $debugInfo);
        // if(!$user->has_set_email){
        //     // イベントハッカ
        //     event(new ExpenseRegistered($categoryDetail));
        // }

        return redirect(route('expense.create'))->with('message', '支出を追加しました。');
    }

    public function destroy(ExpenseCategoryDetail $expenseCategoryDetail):RedirectResponse {
        //削除
        $expenseCategoryDetail->delete();
        //削除したら一覧画面にリダイレクト
        return redirect(route('expense.index'))->with('message', '支出項目を削除しました。');
    }

    // レポート出力
    public function showReport($year=null, $month=null, $targetMonth=null):View
    {
         // 年月日を取得
        $currentDate = Carbon::now();
        $startOfMonth = $currentDate->copy()->startOfMonth()->format('Y-m-d');
        $endOfMonth = $currentDate->copy()->endOfMonth()->format('Y-m-d');
        $yearMonth = $currentDate->copy()->format('Y-m');

         // 年月の設定
         if($year==null){
            $year = (string)$currentDate->year;
        }
        if($month==null){
            $month = (string)$currentDate->month;
        }

        // 一月前の処理
        if($targetMonth == 'pre'){
            $month--;

            if($month < 1) {
                $year--;
                $month = 12;
            }
            $yearMonth = $year . '-' . $month;
            $startOfMonth = Carbon::createFromFormat('Y-m', $yearMonth)->startOfMonth()->format('Y-m-d');
            $endOfMonth = Carbon::createFromFormat('Y-m', $yearMonth)->endOfMonth()->format('Y-m-d');
        }
    
        // 一月先の処理
        if($targetMonth == 'next'){
            $month++;
            
            if($month > 12 ){
                $year++;
                $month = 1;
            }
            $yearMonth = $year . '-' . $month;
            $startOfMonth = Carbon::createFromFormat('Y-m', $yearMonth)->startOfMonth()->format('Y-m-d');
            $endOfMonth = Carbon::createFromFormat('Y-m', $yearMonth)->endOfMonth()->format('Y-m-d');
        }

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
        
        $totalSum = ExpenseCategoryDetail::where([
            ['user_id', $userId],
        ])
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');
        
        $categoryTotals = ExpenseCategory::withSum(['categoryDetails' => function ($query) use ($userId, $startOfMonth, $endOfMonth) {
            $query->where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth]);
        }], 'amount')
        ->get();

        $categoryList = [];
        $amountList = [];

        foreach($categoryTotals as $categoryTotal) {
            array_push($categoryList, $categoryTotal['category']);
            array_push($amountList, $categoryTotal['category_details_sum_amount']);
        }

        return view('report/index', [
            'yearMonth' => $yearMonth,
            'year' => (string)$year,
            'month' => (string)$month,
            'investmentSum' => $investmentSum,
            'consumptionSum' => $consumptionSum,
            'wasteSum' => $wasteSum,
            'categoryList' => $categoryList,
            'amountList' => $amountList,
            'categoryTotals' => $categoryTotals,
            'totalSum' => $totalSum,
        ]);
    }
    // カレンダー出力
    public function renderCalendar():View
    {   
        $currentDate = Carbon::now();
        $startOfMonth = $currentDate->copy()->startOfMonth()->format('Y-m-d');
        $endOfMonth = $currentDate->copy()->endOfMonth()->format('Y-m-d');
        $yearMonth = $currentDate->copy()->format('Y-m');

        $user = Auth::user();
        $userId = $user->id;
        $amounts = ExpenseCategoryDetail::select('date', DB::raw('SUM(amount) as date_amount'))
            ->where([
                ['user_id', $userId],
                ['date', 'LIKE', $yearMonth . '%'],
            ])
            ->groupBy('date')
            ->get();
        
        // 支出合計処理
        $sum = ExpenseCategoryDetail::where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');
        
        $incomeSum = IncomeRecord::where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // 収支
        $incomeAndExpense = $incomeSum - $sum;

        $apiKey = 'e0e333cb6ffb4623a4a111748241402';
        $city = 'Osaka'; // 例: Tokyo
        $lang = 'ja';

        $resp = Http::get("http://api.weatherapi.com/v1/forecast.json?key={$apiKey}&q={$city}&lang={$lang}&&days=7");
        $data = $resp->json();
        $temperature = $data['forecast']['forecastday'];
        // dd($temperature);
        return view('expense/calendar',[
            'amounts' => $amounts,
            'sum' => $sum,
            'incomeSum' => $incomeSum,
            'incomeAndExpense' => $incomeAndExpense,
            'temperature' => $temperature,
        ]);
    }

    public function getCalendar(Request $request){

        // 返すデータ
        $resData = [];


        $user = Auth::user();
        $userId = $user->id;
        $year = $request->year;
        $month = sprintf("%02d", $request->month);
        
        if ($year == Null || $month == Null){
            abort(404);
        }
    
        $yearMonth = $year . '-' . $month;

        $startOfMonth = Carbon::createFromFormat('Y-m', $yearMonth)->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::createFromFormat('Y-m', $yearMonth)->endOfMonth()->format('Y-m-d');


        $amounts = ExpenseCategoryDetail::select('date', DB::raw('SUM(amount) as date_amount'))
            ->where([
                ['user_id', $userId],
                ['date', 'LIKE', $yearMonth . '%'],
            ])
            ->groupBy('date')
            ->get(); 
        
        array_push($resData, $amounts);

        // 収入合計処理
        $incomeSum = IncomeRecord::where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');
        array_push($resData, $incomeSum);
        
        // 支出合計処理
        $sum = ExpenseCategoryDetail::where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');
        array_push($resData, $sum);


        // 収支
        $incomeAndExpense = $incomeSum - $sum;
        array_push($resData, $incomeAndExpense);

        // // 収入
        // $incomeAmounts = IncomeRecord::select('date', DB::raw('SUM(amount) as date_amount'))
        //         ->where([
        //             ['user_id', $userId],
        //             ['date', 'LIKE', $yearMoth . '%'],
        //         ])
        //         ->groupBy('date')
        //         ->get();
        
        // array_push($resData, $incomeAmounts);


        return response()->json($resData);
    }

    // public function renderExpenseCalendar(Request $request)
    // {
    //     $user = Auth::user();
    //     $userId = $user->id;
    //     $year = $request->input('year');
    //     $month = $request->input('month');

    //     $yearMonth = $year . '-' . $month;
    //     $amounts = ExpenseCategoryDetail::select('date', DB::raw('SUM(amount) as date_amount'))
    //         ->where([
    //             ['user_id', $userId],
    //             ['date', 'LIKE', $year . '-' . $month . '%'],
    //         ])
    //         ->groupBy('date')
    //         ->get();
        
    //     return response()->json([
    //         'amounts' => $amounts,
    //     ]);
    // }

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
