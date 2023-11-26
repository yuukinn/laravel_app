<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseCategoryDetail extends Model
{
    use HasFactory;

    public function expenseCategory(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id', 'id');
    }
}
