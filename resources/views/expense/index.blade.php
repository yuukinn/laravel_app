<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@latest/font/bootstrap-icons.css" rel="stylesheet">
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
                <a class="btn btn-primary me-1" href="{{ route('expense.create') }}">支出追加</a>
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
        <!-- <div class="container">
            <div class="row d-flex justify-content-around">
                <div class="card col-4 m-4" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">投資</h5>
                        <p class="card-text">{{ $sum }}円</p>
                        <form action="{{ route('expense.index', ['type' => 'inv.']) }}" method="GET">
                            <input type="hidden" name="type" value="inv.">
                            <button type="submit" class="btn btn-primary">投資一覧</button>
                        </form>
                    </div>
                </div>
                <div class="card col-4 m-4" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">消費</h5>
                        <p class="card-text">{{ $sum }}円</p>
                        <form action="{{ route('expense.index', ['type' => 'cons.']) }}" method="GET">
                            <input type="hidden" name="type" value="cons.">
                            <button type="submit" class="btn btn-primary">消費一覧</button>
                        </form>
                    </div>
                </div>
                <div class="card col-4 m-4" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">浪費</h5>
                        <p class="card-text">{{ $sum }}円</p>
                        <form action="{{ route('expense.index', ['type' => 'waste']) }}" method="GET">
                            <input type="hidden" name="type" value="waste">
                            <button type="submit" class="btn btn-primary">浪費一覧</button>
                        </form>
                    </div>
                </div>
            </div> -->
            <!-- <div class="d-flex justify-content-between">
                <form action="{{ route('expense.index')}}" method="GET">
                    @csrf
                    <input type="hidden" name="type">
                    <button type="submit" class="btn btn-primary">全表示</button>
                </form>

                <form action="{{ route('expense.csv') }}" method="GET">
                    <label class="form-label"  for="investment">投資</label>
                    <input class="form-check-input me-2" type="radio" id="investment" name="asset_type" value="inv.">

                    <label class="form-label" for="wastage">浪費</label>
                    <input class="form-check-input me-2" type="radio" id="wastage" name="asset_type" value="waste">

                    <label class="form-label" for="consumption">消費</label>
                    <input class="form-check-input" type="radio" id="consumption" name="asset_type" value="cons.">

                    <input type="submit" value='CSVダウンロード'>
                </form>
            </div>
        </div> -->
        @if(session('message'))
            <div class="container text-center mt-3 mb-3">
                {{ session('message') }}
            </div>
        @endif
        @foreach($categoryDetails as $categoryDetail)
            @if($categoryDetail->is_investment)
                <x-expense-category-card :categoryDetail="$categoryDetail" class="bg-success"/>
            @elseif($categoryDetail->is_consumption)
                <x-expense-category-card :categoryDetail="$categoryDetail" class="bg-primary"/>
            @else
                <x-expense-category-card :categoryDetail="$categoryDetail" class="bg-danger"/>
            @endif
        @endforeach

        <div>
            {{ $categoryDetails->withQueryString()->links()}}
        </div>

    </x-layouts.expense-manager>
    <footer class="footer py-3 bg-opacity-50 bg-primary mt-4">
    <div class="container d-flex justify-content-around">
        <div>
            <a class="btn btn-primary" href="{{ route('expense.create') }}">支出追加</a>
        </div>
        <div>
            <a class="btn btn-primary" href="{{ route('goal_amount.index') }}">目標金額設定</a>
        </div>
        <div>
            <a class="btn btn-primary" href="{{ route('goal_amount.index') }}">レポート</a>
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

            // const dateButton = document.getElementById('date');
            // const amountButton = document.getElementById('amount');

            // dateButton.addEventListener('click', function () {
            //     // 日付けボタンが押下された場合
            //     console.log(this.innerText);
            //     this.innerText = (this.innerText == '日付：昇順') ? '日付：降順' : '日付：昇順';
            // });

            // amountButton.addEventListener('click', function () {
            //     console.log(this.innerText)
            //     this.innerText = (this.innerText == '金額：昇順') ? '金額：降順' : '金額：昇順';
            // });
        });
    </script>
</body>
</html>