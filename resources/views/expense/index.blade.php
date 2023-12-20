<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <x-layouts.expense-manager>
        <div class="container">
            <h1>支出一覧</h1>
            <hr>
            <a class="btn btn-primary me-3" href="{{ route('expense.create') }}">支出追加</a>
            <a class="btn btn-primary" href="{{ route('goal_amount.index') }}">目標金額設定</a>
        </div>
        <div class="container">
            <div class="row d-flex justify-content-around">
                <div class="card col-4 m-4" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">投資</h5>
                        <p class="card-text">{{ $investmentSum }}円</p>
                        <form action="{{ route('expense.index', ['type' => 'inv.']) }}" method="GET">
                            <input type="hidden" name="type" value="inv.">
                            <button type="submit" class="btn btn-primary">投資一覧</button>
                        </form>
                    </div>
                </div>
                <div class="card col-4 m-4" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">消費</h5>
                        <p class="card-text">{{ $consumptionSum }}円</p>
                        <form action="{{ route('expense.index', ['type' => 'cons.']) }}" method="GET">
                            <input type="hidden" name="type" value="cons.">
                            <button type="submit" class="btn btn-primary">消費一覧</button>
                        </form>
                    </div>
                </div>
                <div class="card col-4 m-4" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">浪費</h5>
                        <p class="card-text">{{ $wasteSum }}円</p>
                        <form action="{{ route('expense.index', ['type' => 'waste']) }}" method="GET">
                            <input type="hidden" name="type" value="waste">
                            <button type="submit" class="btn btn-primary">浪費一覧</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
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
        </div>
        @if(session('message'))
            <div class="">
                {{ session('message') }}
            </div>
        @endif

        <table class="table table-hover container-md mt-3">
            <tr>
                <th>カテゴリ名</th>
                <th>詳細</th>
                <th>金額</th>
                <th>資産タイプ</th>
                <th>日付</th>
                <th>削除ボタン</th>
            </tr>
            @foreach($categoryDetails as $categoryDetail)
                <tr>
                    <td>{{ $categoryDetail->expenseCategory->category}}</td>
                    <td>{{ $categoryDetail->category_detail}}</td>
                    <td>{{ $categoryDetail->amount }}</td>
                    @if( $categoryDetail->is_investment)
                    <td>投資</td>
                    @elseif( $categoryDetail->is_consumption)
                    <td>消費</td>
                    @else
                    <td>浪費</td>
                    @endif
                    <td>{{ $categoryDetail->date }}</td>
                    <td>
                        <form action="{{ route('expense.destroy', $categoryDetail) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="submit" value="削除" class="delete-form">
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </x-layouts.expense-manager>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            //削除ボタンの要素取得
            let deleteButtons = document.querySelectorAll('.delete-form');

            deleteButtons.forEach(function (deleteButton) {
                deleteButton.addEventListener('click', function (event) {
                    // フォームのデフォルトの送信を防ぐ
                    event.preventDefault();
                    if (confirm('本当に削除しますか？')){
                        this.('form')submit();
                    }
                });
            });
        });
    </script>
</body>
</html>