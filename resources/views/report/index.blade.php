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
        <h2 class="text-center mt-4">{{\Carbon\Carbon::createFromFormat('Y-m', $yearMonth)->format('Y年m月')}}</h2>

        <section id="asset_category_tab" name="asset_category_tab">
            <div class="container mb-3" style="width:80%;">
                <canvas id="assetChart"></canvas>
            </div>
            
            <div class="container mt-3">
                <div class="row">
                    <div class="col-4" >
                        <h5>投資</h5>
                        <p>{{ $investmentSum }}円</p>
                    </div>
                    <div class="col-4" >
                        <h5>消費</h5>
                        <p>{{ $consumptionSum }}円</p>
                    </div>
                    <div class="col-4" >
                        <h5>浪費</h5>
                        <p>{{ $wasteSum }}円</p>
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
                    <h5>{{$categoryTotal['category']}}</h5>
                    <p>{{$categoryTotal['category_details_sum_amount'] ? $categoryTotal['category_details_sum_amount'] : '0'}}円</p>
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
                            'rgba(173, 216, 173, 0.8)',  // 明るい緑色
                            'rgba(166, 192, 221, 0.8)', // 明るい青色
                            'rgba(232, 168, 160, 0.8)',  // 明るい赤色
                            'rgba(54, 162, 235, 0.8)',    // 明るい青
                            'rgba(255, 159, 64, 0.8)',    // 明るいオレンジ
                            'rgba(255, 0, 0, 0.8)',       // 明るい赤
                            'rgba(0, 255, 0, 0.8)',       // 明るい緑
                            'rgba(0, 0, 255, 0.8)',       // 明るい青
                            'rgba(128, 0, 128, 0.8)',     // 明るい紫
                            'rgba(255, 255, 0, 0.8)',     // 明るい黄
                            'rgba(0, 255, 255, 0.8)',     // 明るいシアン
                            'rgba(255, 0, 255, 0.8)',     // 明るいマゼンタ
                            'rgba(128, 128, 128, 0.8)',   // 明るいグレー
                            'rgba(255, 165, 0, 0.8)',     // オレンジ
                            'rgba(0, 128, 128, 0.8)',     // ターコイズ
                            'rgba(255, 69, 0, 0.8)',      // レッドオレンジ
                            'rgba(0, 128, 0, 0.8)',       // グリーン
                            'rgba(128, 0, 0, 0.8)',       // マルーン
                            'rgba(70, 130, 180, 0.8)',    // スチールブルー
                            'rgba(255, 215, 0, 0.8)'      // ゴールデンイエロー
                        ],
                        borderColor:[
                            'rgba(173, 216, 173, 1)',  // 明るい緑色
                            'rgba(166, 192, 221, 1)', // 明るい青色
                            'rgba(232, 168, 160, 1)',  // 明るい赤色
                            'rgba(54, 162, 235, 1)',    // 明るい青
                            'rgba(255, 159, 64, 1)',    // 明るいオレンジ
                            'rgba(255, 0, 0, 1)',       // 明るい赤
                            'rgba(0, 255, 0, 1)',       // 明るい緑
                            'rgba(0, 0, 255, 1)',       // 明るい青
                            'rgba(128, 0, 128, 1)',     // 明るい紫
                            'rgba(255, 255, 0, 1)',     // 明るい黄
                            'rgba(0, 255, 255, 1)',     // 明るいシアン
                            'rgba(255, 0, 255, 1)',     // 明るいマゼンタ
                            'rgba(128, 128, 128, 1)',   // 明るいグレー
                            'rgba(255, 165, 0, 1)',     // オレンジ
                            'rgba(0, 128, 128, 1)',     // ターコイズ
                            'rgba(255, 69, 0, 1)',      // レッドオレンジ
                            'rgba(0, 128, 0, 1)',       // グリーン
                            'rgba(128, 0, 0, 1)',       // マルーン
                            'rgba(70, 130, 180, 1)',    // スチールブルー
                            'rgba(255, 215, 0, 1)'      // ゴールデンイエロー
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
        })

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