<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GoalAmount;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class GoalAmountController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $userID = $user->id;

        try {
            $goalAmounts = GoalAmount::where('user_id', $userID)->firstOrFail();

            return view('goal_amount/index', [
                'goalAmount' => $goalAmounts->goal_amount,
                'goalDate' => $goalAmounts->goal_date
            ]);
        } catch (ModelNotFoundException $e){

            return view('goal_amount/index', [
                'goalAmount' => 0,
                'goalDate' => "未設定"
            ]);
            
        }
    }

    public function store(Request $request):RedirectResponse
    {
        $goalAmount = new GoalAmount();

        $user = Auth::user();
        $userID = $user->id;

        $goalAmount->user_id = $userID;
        $goalAmount->goal_amount = $request->goal_amount;
        $goalAmount->goal_date = $request->goal_date;

        $goalAmount->save();

        return redirect(route('goal_amount.index'));

    }
}
