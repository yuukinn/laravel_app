<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseCategoryDetailController;
use App\Http\Controllers\GoalAmountController;
use App\Http\Controllers\IncomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::prefix('expense/create')
     ->middleware(['auth', 'verified'])
     ->name('expense.')
     ->controller(CategoryController::class)
     ->group(function () {
          Route::get('{date?}', 'create')->name('create');
          Route::post('', 'store')->name('store');
     });

Route::post('expense/create/detail', [ExpenseCategoryDetailController::class, 'storeDetail'])
     ->middleware(['auth', 'verified'])
     ->name('expense.detail.store');
Route::get('expense/index/{type?}/{year?}/{month?}/{targetmonth?}', [ExpenseCategoryDetailController::class, 'getCategoryDetails'])
     ->middleware(['auth', 'verified'])
     ->where([
          'type' => 'inv\.|cons\.|waste|date_asc|date_desc|amount_asc|amount_desc',
          'targetmonth' => 'pre|next',
          'year' => '\d{4}',
          'month' => '\d{1,2}',
      ])
     ->name('expense.index');
// カレンダー
Route::get('expense/calendar', [ExpenseCategoryDetailController::class, 'renderCalendar'])
     ->middleware(['auth', 'verified'])
     ->name('expense.calendar');
Route::get('expense/calendar/detail', [ExpenseCategoryDetailController::class, 'getCalendar'])
     ->middleware(['auth', 'verified'])
     ->name('expense.data');
// 収入
Route::prefix('income/create')
     ->middleware(['auth', 'verified'])
     ->name('income.')
     ->controller(IncomeController::class)
     ->group(function (){
          Route::post('', 'store')->name('store');
     });
// Route::get('expense/index/{page_id?}', [ExpenseCategoryDetailController::class, 'getCategoryDetails'])
//      ->middleware(['auth', 'verified'])
//      ->name('expense.index');

// Route::get('expense/download/index', [ExpenseCategoryDetailController::class, 'exportCsv'])
//      ->middleware(['auth', 'verified'])
//      ->name('expense.csv');

Route::delete('expense/destroy/{expenseCategoryDetail}', [ExpenseCategoryDetailController::class, 'destroy'])
     ->middleware(['auth', 'verified'])
     ->name('expense.destroy');
Route::get('goal_amount/index', [GoalAmountController::class, 'index'])
     ->middleware(['auth', 'verified'])
     ->name('goal_amount.index');
Route::post('goal_amount/store', [GoalAmountController::class, 'store'])
     ->middleware(['auth', 'verified'])
     ->name('goal_amount.store');
Route::put('goal_amount/edit/{goalAmounts}', [GoalAmountController::class, 'update'])
     ->middleware(['auth', 'verified'])
     ->name('goal_amount.edit');
// レポート出力
Route::get('report/index/{year?}/{month?}/{targetmonth?}', [ExpenseCategoryDetailController::class, 'showReport'])
     ->middleware(['auth', 'verified'])
     ->name('report.index');