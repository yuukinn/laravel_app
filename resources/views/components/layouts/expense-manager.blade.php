<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Document</title>
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- <div class="container">
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <div class="col-md-3 mb-2 mb-md-0">
                <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
                    <svg class="bi" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
                </a>
            </div>

            <h3 class="justify-content-center">家計簿</h3>

            <div class="col-md-3 text-end">
                <form action="{{ route('logout')}}" method="POST">
                    @csrf
                    <input type="submit" class="btn btn-outline-primary me-2" value="log out">
                </form> 
            </div>
        </header>
    </div> -->
<nav class="navbar bg-body-tertiary d-flex justify-content-between">
  <div class="col-md-3 ms-2">
    <span class="navbar-brand mb-0 h1 fw-bold">家計簿</span>
  </div>
  <div class="col-md-3 text-end">
    <form action="{{ route('logout')}}" method="POST">
        @csrf
        <input type="submit" class="btn btn-outline-primary me-2" value="log out">
    </form> 
  </div> 
</nav>
    <main class="flex-grow-1 container">
        {{ $slot }}
    </main>
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
</body>
</html>