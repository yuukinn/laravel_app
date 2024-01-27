<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta namep="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@latest/font/bootstrap-icons.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
    <x-layouts.expense-manager>
        <div class="container-md w-75 mt-5 text-center">
            @if ($errors->any())
                <x-error-messages :errors="$errors"/>
            @endif
        </div>
        @if($goalAmount == 0 )
        <div class="container  bg-info rounded-top w-75 p-3">
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
        @else
        <div class="container  rounded-top w-75 p-3">
            <h2 class="ps-2">目標金額設定</h2>
            <form action="{{ route('goal_amount.edit', $goalAmounts) }}" method="POST" class="p-3">
                @csrf
                @method('PUT')
                <div>
                    <input type="date" name="goal_date" class="form-control mb-3" value="{{ $goalAmounts->goal_date }}">
                </div>
                <div>
                    <input type="number" name="goal_amount" class="form-control mb-3" value="{{ $goalAmounts->goal_amount }}">
                </div>
                <input type="submit" value="編集" class="btn btn-primary">
            </form>
        </div>
            <div>
                <a href="{{ route('expense.index') }}" class="btn bg-opacity-50 bg-secondary">一覧へ</a>
            </div>
        @endif
    </x-layouts.expense-manager>
</body>
</html>