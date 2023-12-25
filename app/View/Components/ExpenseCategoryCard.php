<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\ExpenseCategoryDetail;

class ExpenseCategoryCard extends Component
{
    public ExpenseCategoryDetail $categoryDetail;
    public $class;
    /**
     * Create a new component instance.
     */
    public function __construct(ExpenseCategoryDetail $categoryDetail, $class)
    {
        //
        $this->categoryDetail = $categoryDetail;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.expense-category-card');
    }
}
