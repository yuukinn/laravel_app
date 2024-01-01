<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
</head>
<body>
    <x-layouts.expense-manager>
    <h2 class="text-center mt-4">{{\Carbon\Carbon::createFromFormat('Y-m', $yearMonth)->format('Y年m月')}}</h2>
    <div class="container" style="width:80%;">
        <canvas id="myChart"></canvas>
    </div>
    
    <div class="container mt-3">
        <p>投資：{{ $investmentSum }}円</p>
        <p>消費：{{ $consumptionSum }}円</p>
        <p>浪費：{{ $wasteSum }}円</p>
    </div>
    <div>
        <a href="{{ route('expense.index') }}" class="btn bg-opacity-50 bg-secondary">一覧へ</a>
    </div>
    </x-layouts.expense-manager>
    <script>
        let ctx = document.getElementById('myChart').getContext('2d');
        
        let myChat = new Chart(ctx, {
            type: 'pie', // チャートの種類を円グラフに変更
            data: {
                labels: ['投資', '消費', '浪費'],
                datasets:[{
                    // 円グラフの各セクションのデータ
                    data:[@json($investmentSum), @json($consumptionSum), @json($wasteSum)],
                    // 各セクションの背景色
                    backgroundColor:[
                        'rgba(173, 216, 173, 0.8)',  // 明るい緑色
                        'rgba(166, 192, 221, 0.8)', // 明るい青色
                        'rgba(232, 168, 160, 0.8)',  // 明るい赤色
                    ],
                    borderColor:[
                        'rgba(173, 216, 173, 1)',  // 明るい緑色
                        'rgba(166, 192, 221, 1)', // 明るい青色
                        'rgba(232, 168, 160, 1)',  // 明るい赤色
                    ],
                    borderWidth:1
                }]
            },
            options:{
                responsive:true,
                maintainAspectRatio: false, // アスペクト比を維持しない
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>