<div class="mb-2">
    <div class="card card">
        <div class="card-header d-flex justify-content-between p-1 {{ $class }}">
            @if($class == 'bg-success')
                <p class="mb-0 d-flex align-items-center"><i class="bi bi-graph-up-arrow pe-1" style="font-size: 1.5rem;"></i>{{ $expenseDetail->expenseCategory->category }} </p>
            @elseif($class == 'bg-primary')
                <p class="mb-0 d-flex align-items-center"><i class="bi bi-cart3 pe-1" style="font-size: 1.5rem;"></i>{{ $expenseDetail->expenseCategory->category }} </p>
            @else
                <p class="mb-0 d-flex align-items-center"><i class="bi bi-cash-coin pe-1" style="font-size: 1.5rem;"></i>{{ $expenseDetail->expenseCategory->category }} </p>
            @endif
            <form action="{{ route('expense.destroy', $expenseDetail) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn p-0 delete-form"><i class="bi bi-trash" style="font-size: 1.5rem;"></i></button>
            </form>
        </div>
        <div class="card-body d-flex justify-content-between p-2">
            <p class="mb-0">{{ $expenseDetail->category_detail}}</p>
            <p class="mb-0" id="index-amount">￥{{ number_format($expenseDetail->amount) }}</p>
        </div>
        <span class="text-end">{{\Carbon\Carbon::createFromFormat('Y-m-d', $expenseDetail->date)->format('Y年m月d日') }}</span>
    </div>
</div>