<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Document</title>
</head>
<body class="d-flex flex-column min-vh-100">
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

</body>
</html>