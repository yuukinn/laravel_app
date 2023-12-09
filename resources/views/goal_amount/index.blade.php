<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta namep="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <x-layouts.expense-manager>
        <div class="container  bg-info rounded-top w-25 ">
            <h2>目標金額</h2>
            <h3>{{ $goalAmount }}円</h3>
            <h3>期間:{{ $goalDate }}</h3>
            <div>
                <a href="{{ route('expense.index') }}" class="btn btn-primary">一覧へ</a>
            </div>
            <div class="pb-4">
                <form action="{{ route('goal_amount.store') }}" method="POST">
                    @csrf
                    <div>
                        <label class="text-left">目標期間</label>
                        <input type="date" name="goal_date" class="form-control">
                    </div>
                    <div>
                        <label>目標金額</label>
                        <input type="number" name="goal_amount" class="form-control">
                    </div>
                    <div class="mt-4">
                        <input type="submit" value="追加" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </x-layouts.expense-manager>
</body>
</html>