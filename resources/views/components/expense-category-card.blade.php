<div class="mb-2">
    <div class="card card bg-opacity-50 {{ $class }}">
        <div class="card-header d-flex justify-content-between p-1">
            @if($class == 'bg-success')
                <p class="mb-0 d-flex align-items-center"><i class="bi bi-graph-up-arrow pe-1" style="font-size: 1.5rem;"></i>{{ $categoryDetail->expenseCategory->category }} </p>
            @elseif($class == 'bg-primary')
                <p class="mb-0 d-flex align-items-center"><i class="bi bi-cart3 pe-1" style="font-size: 1.5rem;"></i>{{ $categoryDetail->expenseCategory->category }} </p>
            @else
                <p class="mb-0 d-flex align-items-center"><i class="bi bi-cash-coin pe-1" style="font-size: 1.5rem;"></i>{{ $categoryDetail->expenseCategory->category }} </p>
            @endif
            <form action="{{ route('expense.destroy', $categoryDetail) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn p-0 delete-form"><i class="bi bi-trash" style="font-size: 1.5rem;"></i></button>
            </form>
        </div>
        <div class="card-body d-flex justify-content-between p-2">
            <p class="mb-0">{{ $categoryDetail->category_detail}}</p>
            <p class="mb-0">￥{{ number_format($categoryDetail->amount) }}</p>
        </div>
        <span class="text-end">{{\Carbon\Carbon::createFromFormat('Y-m-d', $categoryDetail->date)->format('Y年m月d日') }}</span>
    </div>
</div>