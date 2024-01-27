<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
    <title>Document</title>
</head>
<body class="d-flex flex-column min-vh-100">
  <nav class="navbar d-flex justify-content-between header">
    <div class="col-md-3 ms-2">
      <span class="navbar-brand mb-0 h1 fw-bold header-text">家計簿</span>
    </div>
    <div class="col-md-3 text-end">
      <form action="{{ route('logout')}}" method="POST">
          @csrf
          <input type="submit" class="btn me-2" id="logout-btn" value="log out">
      </form> 
    </div> 
  </nav>
    <main class="flex-grow-1 container mb-5 pb-5">
        {{ $slot }}
    </main>
    <footer class="footer mt-5 fixed-bottom py-2">
      <div class="container d-flex justify-content-around">
          <div class="text-center">
             <a class="text-decoration-none" href="{{ route('expense.index') }}"><i class="bi bi-house fs-1 icon"></i>
             <p class="mb-0 text-black">ホーム</p>
            </a>
          </div>
          <div class="text-center">
              <a class="text-decoration-none" href="{{ route('expense.create') }}"><i class="bi bi-plus-lg fs-1 icon text-center"></i>
              <p class="mb-0 text-black">新規追加</p>
            </a>
          </div>
          <div class="text-center">
              <a class="text-decoration-none" href="{{ route('goal_amount.index') }}"><i class="bi bi-bullseye fs-1 icon"></i>
              <p class="mb-0 text-black">目標設定</p>
            </a>
          </div>
          <div class="text-center">
              <a class="text-decoration-none" href="{{ route('report.index') }}"><i class="bi bi-pie-chart-fill fs-1 icon"></i>
              <p class="mb-0 text-black">分析</p>
            </a>
          </div>
        </div>
    </footer>
</body>
</html>