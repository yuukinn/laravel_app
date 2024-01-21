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
        <div class="d-flex justify-content-center my-2 bg-info">
            <h3>支出金額
                {{ number_format($sum) }}円
            </h3>
        </div>

        <h4 class="text-center">{{ \Carbon\Carbon::createFromFormat('Y-m', $yearMonth)->format('Y年m月') }}</h3>
        <div class="d-flex justify-content-between mt-4">
            <div class="">
                <a class="btn me-1 add-expnese-btn" href="{{ route('expense.create') }}">支出追加</a>
            </div>
            <div class="d-flex justify-content-end">
                <form action="{{ route('expense.index', ['type' => $date_type]) }}" method="GET" class="input-group mb-3 container d-flex justify-content-end p-0">
                    <button class="btn btn-outline-secondary" type="submit" name="type" id="date" value="date_asc">日付</button>
                </form>
                <form action="{{ route('expense.index', ['type' => $amount_type]) }}" method="GET" class="input-group mb-3 container d-flex justify-content-end p-0">
                    <button class="btn btn-outline-secondary" type="submit" name="type" id="amount" value="amount_asc">金額</button>
                </form>
            </div>
           
        </div>
        @if(session('message'))
            <div class="container text-center mt-3 mb-3">
                {{ session('message') }}
            </div>
        @endif
        @if($categoryDetails->isNotEmpty())
        @foreach($categoryDetails as $categoryDetail)
            @if($categoryDetail->is_investment)
                <x-expense-category-card :categoryDetail="$categoryDetail" class="bg-success"/>
            @elseif($categoryDetail->is_consumption)
                <x-expense-category-card :categoryDetail="$categoryDetail" class="bg-primary"/>
            @else
                <x-expense-category-card :categoryDetail="$categoryDetail" class="bg-danger"/>
            @endif
        @endforeach
        @else
            <p class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">no data</p>
        @endif
        <div>
            {{ $categoryDetails->withQueryString()->links()}}
        </div>

    </x-layouts.expense-manager>
    <footer class="footer py-3 mt-4">
    <div class="container d-flex justify-content-around">
        <div>
            <a class="btn btn-primary" href="{{ route('expense.create') }}">支出追加</a>
        </div>
        <div>
            <a class="btn btn-primary" href="{{ route('goal_amount.index') }}">目標金額設定</a>
        </div>
        <div>
            <a class="btn btn-primary" href="{{ route('report.index') }}">レポート</a>
        </div>
    </div>
</footer>
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