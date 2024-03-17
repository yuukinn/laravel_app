<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@latest/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <title>Document</title>
</head>
<body>
    <x-layouts.expense-manager>
        <div class="my-3">
            <div class="d-flex justify-content-evenly">
                <h2 class="text-primary">
                    <p class="item mb-0 text-center" style="color:black">収入</p>
                    {{ "￥" .number_format($incomeSum) }}
                </h2>
                <h2 class="d-flex align-items-center pt-3">
                    -
                </h2>
                <h2 class="text-danger">
                    <p class="item mb-0 text-center" style="color:black">支出</p>
                    {{ "￥" .number_format($sum) }}
                </h2>
                <h2 class="d-flex align-items-center pt-3">
                    =
                </h2>
                @if($incomeAndExpense > 0)
                <h2 class="text-primary">
                        <p class="item mb-0 text-center" style="color:black">収支</p>
                        {{ "￥" . number_format($incomeAndExpense) }}
                </h2>
                @else
                <h2 class="text-danger">
                        <p class="item mb-0 text-center" style="color:black">収支</p>
                        {{ "￥" . number_format($incomeAndExpense) }}
                </h2>
                @endif
            </div>
        </div>
        <!-- 年月 -->
        <h3 class="text-center">
            <a style="text-decoration:none;" class="arrow" href="{{ route('expense.index', ['type' => $date_type, 'year' => $year, 'month' => $month, 'targetmonth' => 'pre']) }}"><<</a>
            {{ \Carbon\Carbon::createFromFormat('Y-m', $yearMonth)->format('Y年m月') }}
            <a style="text-decoration:none;" class="arrow" href="{{ route('expense.index', ['type' => $date_type, 'year' => $year, 'month' => $month, 'targetmonth' => 'next']) }}">>></a>
        </h3>
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('expense.calendar') }}"><i class="bi bi-calendar-date fs-1 cala-icon"></i></a>
            <div class="d-flex justify-content-end">
                <form action="{{ route('expense.index', ['type' => $date_type, 'year' => $year, 'month' => $month ]) }}" method="GET" class="input-group mb-3 container d-flex justify-content-end p-0">
                    <button class="btn btn-outline-secondary" type="submit"  id="date" >日付</button>
                </form>
                <form action="{{ route('expense.index', ['type' => $amount_type, 'year' => $year, 'month' => $month ]) }}" method="GET" class="input-group mb-3 container d-flex justify-content-end p-0">
                    <button class="btn btn-outline-secondary" type="submit"  id="amount">金額</button>
                </form>
            </div>
        </div>
        @if(session('message'))
            <div class="container text-center mt-3 mb-3">
                {{ session('message') }}
            </div>
        @endif
        @if($expenseDetails->isNotEmpty())
            @foreach($expenseDetails as $expenseDetail)
                @if($expenseDetail->is_investment)
                    <x-expense-category-card :expenseDetail="$expenseDetail" class="investment-color"/>
                @elseif($expenseDetail->is_consumption)
                    <x-expense-category-card :expenseDetail="$expenseDetail" class="consumption-color"/>
                @else
                    <x-expense-category-card :expenseDetail="$expenseDetail" class="waste-color"/>
                @endif
            @endforeach
        @else
            <p class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">no data</p>
        @endif
        <div>
            {{ $expenseDetails->withQueryString()->links()}}
        </div>

    </x-layouts.expense-manager>
    <script>
        window.FontAwesomeConfig = { autoReplaceSvg: 'nest' };
        document.addEventListener('DOMContentLoaded', function(){
            //削除ボタンの要素取得
            let deleteButtons = document.querySelectorAll('.delete-form');

            deleteButtons.forEach(function (deleteButton) {
                deleteButton.addEventListener('click', function (event) {
                    // フォームのデフォルトの送信を防ぐ
                    event.preventDefault();
                    if (confirm('本当に削除しますか？')){
                        this.closest('form').submit();
                    };
                });
            });
        });
    </script>
</body>
</html>