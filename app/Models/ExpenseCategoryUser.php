<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategoryUser extends Model
{
    use HasFactory;
    protected $table = 'expense_category_user';
    protected $fillable = ['user_id', 'expense_category_id'];
}
