<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ExpenseCategory extends Model
{
    use HasFactory;

    public function categoryDetails(): HasMany
    {
        return $this->hasMany(ExpenseCategoryDetail::class);
    }
    
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();      
    }
}

