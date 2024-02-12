<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@latest/font/bootstrap-icons.css" rel="stylesheet">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
</head>
<body>
    <x-layouts.expense-manager>
        <ul class="nav nav-tabs w-80 mt-4 container">
            <li class="nav-item">
                <a class="nav-link active tab-color text-black" id="asset_tab" data-tab-id="asset_category_tab" aria-current="page">資産別</a>
            </li >
            <li class="nav-item">
                <a class="nav-link text-black" id="category_tab" data-tab-id="categories_tab">カテゴリ別</a>
            </li>
        </ul>
         <!-- 年月 -->
         <h3 class="text-center my-2">
            <a style="text-decoration:none;" class="arrow" href="{{ route('report.index', ['year' => $year, 'month' => $month, 'targetmonth' => 'pre']) }}"><<</a>
            {{ \Carbon\Carbon::createFromFormat('Y-m', $yearMonth)->format('Y年m月') }}
            <a style="text-decoration:none;" class="arrow" href="{{ route('report.index', ['year' => $year, 'month' => $month, 'targetmonth' => 'next']) }}">>></a>
        </h3>

        <section id="asset_category_tab" name="asset_category_tab">
            <div class="container mb-3" style="width:80%;">
                <canvas id="assetChart"></canvas>
            </div>
            
            <div class="container mt-3">
                <div class="row">
                    <div class="col-4" >
                        <h5 style="background-color: rgba(173, 216, 173, 0.8);">投資</h5>
                        <p style="font-weight: bold;  font-size: 20px;">{{ $investmentSum }}円</p>
                    </div>
                    <div class="col-4" >
                        <h5 style="background-color: rgba(166, 192, 221, 0.8);">消費</h5>
                        <p style="font-weight: bold;  font-size: 20px;">{{ $consumptionSum }}円</p>
                    </div>
                    <div class="col-4" >
                        <h5 style="background-color: rgba(232, 168, 160, 0.8);">浪費</h5>
                        <p style="font-weight: bold;  font-size: 20px;">{{ $wasteSum }}円</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="categories_tab" name="categories_tab">
            <div class="container-fluid" style="width:80; height:200px">
                <canvas id="categoryChart"></canvas>
            </div>
            <div class="container mt-4">
                <div class="row">
                @foreach($categoryTotals as $categoryTotal)
                    <div class="col-4 p-1 mb-3">
                        <div class="category-info  bg-light rounded">
                    <h5 id="{{ $categoryTotal['category'] }}">{{$categoryTotal['category']}}</h5>
                    <p style="font-weight: bold;  font-size: 20px;">{{$categoryTotal['category_details_sum_amount'] ? $categoryTotal['category_details_sum_amount'] : '0'}}円</p>
                </div>
                    </div>
                @endforeach
                </div>
            </div>
        </section>
    </x-layouts.expense-manager>


    <script>
        document.addEventListener('DOMContentLoaded', function(){
            // 資産別グラフ
            let ctx = document.getElementById('assetChart').getContext('2d');
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

            // カテゴリ別グラフ
            let categoryCtx = document.getElementById('categoryChart').getContext('2d');
            let categoryChart = new Chart(categoryCtx, {
                type: 'pie',
                data:{
                    labels:@json($categoryList),
                    datasets:[{
                        //円グラフの各セクションのデータ
                        data:@json($amountList),
                        // 各セクションの背景色
                        backgroundColor:[
                            'rgba(173, 216, 173, 0.8)',   // 明るい緑色
                            'rgba(142, 200, 120, 0.8)',   // 明るい黄緑色
                            'rgba(150, 150, 200, 0.8)',   // 明るい青紫色
                            'rgba(220, 150, 150, 0.8)',   // 明るい赤色
                            'rgba(100, 120, 200, 0.8)',   // 明るい青色
                            'rgba(220, 120, 80, 0.8)',    // 明るいオレンジ
                            'rgba(220, 80, 80, 0.8)',     // 明るい赤
                            'rgba(80, 160, 80, 0.8)',     // 明るい緑色
                            'rgba(80, 80, 200, 0.8)',     // 明るい青色
                            'rgba(160, 80, 160, 0.8)',    // 明るい紫色
                            'rgba(200, 200, 100, 0.8)',   // 明るい黄色
                            'rgba(80, 200, 200, 0.8)',    // 明るいシアン
                            'rgba(200, 80, 200, 0.8)',    // 明るいマゼンタ
                            'rgba(173, 173, 173, 0.8)',   // 明るいグレー
                            'rgba(200, 160, 80, 0.8)',    // 明るいオレンジ
                            'rgba(80, 120, 120, 0.8)',    // 明るいターコイズ
                            'rgba(220, 120, 80, 0.8)',    // 明るいレッドオレンジ
                            'rgba(80, 120, 80, 0.8)',     // 明るい緑
                            'rgba(140, 80, 80, 0.8)',     // 明るいマルーン
                            'rgba(120, 150, 180, 0.8)',   // 明るいスチールブルー
                            'rgba(200, 180, 80, 0.8)',    // 明るいゴールデンイエロー
                        ],
                        borderColor:[
                            'rgba(173, 216, 173, 0.8)',   // 明るい緑色
                            'rgba(142, 200, 120, 0.8)',   // 明るい黄緑色
                            'rgba(150, 150, 200, 0.8)',   // 明るい青紫色
                            'rgba(220, 150, 150, 0.8)',   // 明るい赤色
                            'rgba(100, 120, 200, 0.8)',   // 明るい青色
                            'rgba(220, 120, 80, 0.8)',    // 明るいオレンジ
                            'rgba(220, 80, 80, 0.8)',     // 明るい赤
                            'rgba(80, 160, 80, 0.8)',     // 明るい緑色
                            'rgba(80, 80, 200, 0.8)',     // 明るい青色
                            'rgba(160, 80, 160, 0.8)',    // 明るい紫色
                            'rgba(200, 200, 100, 0.8)',   // 明るい黄色
                            'rgba(80, 200, 200, 0.8)',    // 明るいシアン
                            'rgba(200, 80, 200, 0.8)',    // 明るいマゼンタ
                            'rgba(173, 173, 173, 0.8)',   // 明るいグレー
                            'rgba(200, 160, 80, 0.8)',    // 明るいオレンジ
                            'rgba(80, 120, 120, 0.8)',    // 明るいターコイズ
                            'rgba(220, 120, 80, 0.8)',    // 明るいレッドオレンジ
                            'rgba(80, 120, 80, 0.8)',     // 明るい緑
                            'rgba(140, 80, 80, 0.8)',     // 明るいマルーン
                            'rgba(120, 150, 180, 0.8)',   // 明るいスチールブルー
                            'rgba(200, 180, 80, 0.8)',    // 明るいゴールデンイエロー
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

            const colors = [
                'rgba(173, 216, 173, 0.8)',   // 明るい緑色
                'rgba(142, 200, 120, 0.8)',   // 明るい黄緑色
                'rgba(150, 150, 200, 0.8)',   // 明るい青紫色
                'rgba(220, 150, 150, 0.8)',   // 明るい赤色
                'rgba(100, 120, 200, 0.8)',   // 明るい青色
                'rgba(220, 120, 80, 0.8)',    // 明るいオレンジ
                'rgba(220, 80, 80, 0.8)',     // 明るい赤
                'rgba(80, 160, 80, 0.8)',     // 明るい緑色
                'rgba(80, 80, 200, 0.8)',     // 明るい青色
                'rgba(160, 80, 160, 0.8)',    // 明るい紫色
                'rgba(200, 200, 100, 0.8)',   // 明るい黄色
                'rgba(80, 200, 200, 0.8)',    // 明るいシアン
                'rgba(200, 80, 200, 0.8)',    // 明るいマゼンタ
                'rgba(173, 173, 173, 0.8)',   // 明るいグレー
                'rgba(200, 160, 80, 0.8)',    // 明るいオレンジ
                'rgba(80, 120, 120, 0.8)',    // 明るいターコイズ
                'rgba(220, 120, 80, 0.8)',    // 明るいレッドオレンジ
                'rgba(80, 120, 80, 0.8)',     // 明るい緑
                'rgba(140, 80, 80, 0.8)',     // 明るいマルーン
                'rgba(120, 150, 180, 0.8)',   // 明るいスチールブルー
                'rgba(200, 180, 80, 0.8)',    // 明るいゴールデ 
            ];

            const categoryTotals = @json($categoryTotals);
            let i = 0;
            categoryTotals.forEach(function(categoryTotal){
                let text = document.getElementById(categoryTotal.category);
                if(text){
                    text.style.backgroundColor  = colors[i];
                }
                i ++;
            })

            // タブ切り替え
            let tabLinks = document.querySelectorAll('.nav-link');
            tabLinks.forEach(function(tabLink) {
                tabLink.addEventListener('click', function(e){
                    let sectionId = this.getAttribute('data-tab-id');
                    let tabId = this.id;
                    activateTab(tabId);
                    showSection(sectionId);
                });
            });

            // ローカルストレージから保存されたタブIDを取得
            let savedSectionId = localStorage.getItem('selectedSectionId');
            let savedTabId = localStorage.getItem('selectedTabId');

            // 初回の場合や保存されたタブIDが見つからない場合はデフォルトのタブを表示する
            if (!savedSectionId && !savedTabId) {
                savedSectionId = 'asset_category_tab';
                savedTabId = 'asset_tab';
            }

            activateTab(savedTabId);
            showSection(savedSectionId);
        });

        function activateTab(tabId){
            // すべてのタブを非active状態にする
            let tabs = document.querySelectorAll('.nav-link');
            tabs.forEach(function(tab) {
                tab.classList.remove('active', 'tab-color', 'text-white');
                tab.removeAttribute('aria-current');
            });

            // 選択されたタブをactive状態にする
            let selectedTab = document.getElementById(tabId)
            if(selectedTab) {
                selectedTab.classList.add('active', 'tab-color', 'text-white');

                // 選択されたタブIDをローカルストレージに保存
                localStorage.setItem('selectedTabId', tabId);
            }
        }


        function showSection(sectionId) {
            // すべてのセクションを非表示にする
            let sections = document.querySelectorAll('section');
            sections.forEach(function(section) {
                section.classList.add('d-none');
            });

            // 選択されたセクションを表示する
            let selectedSection = document.getElementById(sectionId);
            if (selectedSection) {
                selectedSection.classList.remove('d-none');

                localStorage.setItem('selectedSectionId', sectionId);
            }
        }
    </script>
</body>
</html>