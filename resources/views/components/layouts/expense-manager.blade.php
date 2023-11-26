<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="container">
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
    </div>
    <main>
        {{ $slot }}
    </main>

</body>
</html>