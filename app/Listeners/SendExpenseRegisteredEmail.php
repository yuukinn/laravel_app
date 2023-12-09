<?php

namespace App\Listeners;

use App\Events\ExpenseRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\NewsEmail;
use App\Models\ExpenseCategoryDetail;
use App\Models\GoalAmount;

class SendExpenseRegisteredEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ExpenseRegistered $event): void
    {
        // ここでメール送信処理を行う
        $user = Auth::user();
        $userName = $user->name;
        $userMail = $user->email;
        $userID = $user->id;

        $achievementThreshold = config('constants.ACHIEVEMENT_THRESHOLD');

        // 目標金額を取得する
        $goalAmountInfo = GoalAmount::where('user_id', $userID)->firstOrFail();
        // 支出の合計を取得する
        $totalExpenseAmounts = ExpenseCategoryDetail::where('user_id', $userID)->sum('amount');

        $goalAmount = $goalAmountInfo->goal_amount;

        if($goalAmount !== 0){
            $goalRatio = $totalExpenseAmounts / $goalAmount * 100;
        } else {
            return;
        }

        $achievementThresholdFloat = (float)$achievementThreshold;

        if ($goalRatio >= $achievementThresholdFloat && !$user->has_set_email) {
            try {


                Mail::send(new NewsEmail($userName, $userMail, $goalAmount, $totalExpenseAmounts));
                // メールが送信されたら、データベースにその情報を保存
                $user->update(['has_set_email' => true]);

            } catch (\Exception $e) {
                dd($e->getMessage());
            }

        }
    }
}
