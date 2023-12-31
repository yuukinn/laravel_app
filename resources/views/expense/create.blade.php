<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Document</title>
</head>
<body>
    <x-layouts.expense-manager>
        <div class="container w-75 mt-4">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active text-reset" id="expense_tab" data-tab-id="add_expense_form" aria-current="page">支出追加</a>
                </li >
                <li class="nav-item">
                    <a class="nav-link text-reset" id="category_tab" data-tab-id="add_category_form">カテゴリ追加</a>
                </li>
            </ul>
            <div class="container-md w-80 mt-5 mb-4 text-center">
                @if ($errors->any() || session('message'))
                    <x-error-messages :errors="$errors" />
                    {{session('message')}}
                @endif
            </div>
            <form id="add_expense_form" name="add_expense_form" class="mt-4" action='{{ route("expense.detail.store") }}' method='POST'>
                <div class="d-flex justify-content-between">
                    <h3>支出追加</h3>
                    <div>
                        <a href="{{ route('expense.index') }}" class="btn bg-opacity-50 bg-secondary">一覧へ</a>
                    </div>
                </div>
                @csrf
                <div class="mb-3">
                    <label class="form-label" for='category'>カテゴリ</label>
                    <select class="form-select" name="category_id" id="category">
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @if($category->id == old('category_id')) selected @endif>
                            {{ $category->category }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">詳細</label>
                    <input class="form-control" type="text" name='category_detail' value="{{ old('category_detail') }}">
                    <span class="small text-danger">※30文字以下まで登録可能です。</span>
                </div>
                <div class="mb-3">
                    <label class="form-label"><span class="text-danger">※</span>金額</label>
                    <input class="form-control" type="number" name='price' id="price" value="{{ old('price') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label"><span class="text-danger">※</span>日付</label>
                    <input class="form-control" type="date" name="date" id="date" value="{{ old('date')?? $currentDateFormatted}}">
                </div>
                <div class="mb-3">
                    <div>
                        <label class="form-label"><span class="text-danger">※</span>資産タイプ</label>
                    </div>
                    <label class="form-label" for="consumption">消費</label>
                    <input class="form-check-input me-2" type="radio" id="consumption" name="asset_type" value="消費" {{ old('asset_type') === "消費" ? "checked" : '' }}>

                    <label class="form-label" for="wastage">浪費</label>
                    <input class="form-check-input me-2" type="radio" id="wastage" name="asset_type" value="浪費" {{ old('asset_type') === "浪費" ? "checked" : ''}}>

                    <label for="investment">投資</label>
                    <input class="form-check-input me-2" type="radio" id="investment" name="asset_type" value="投資" {{ old('asset_type') === "投資" ? "checked" : '' }}>

                </div>
                <input type="hidden" value="{{ $user->id }}" name="user_id">
                <input type="hidden" value="{{ $user->has_set_email }}" name="email_flag">
                <input type="reset" value="リセット" class="btn btn-warning">
                <input type="submit" value="追加" class="btn btn-primary">
            </form>
            <form  action="{{ route('expense.store') }}"   method="POST" id="add_category_form" name="add_category_form" class="container  text-center">
                @csrf
                <h3>カテゴリ追加</h3>
                <div class="mt-5">
                    <input type="text" name="category">
                    <div class="mt-5 d-flex justify-content-between">
                        <div>
                            <input type="submit" class="btn btn-primary" value="追加">
                        </div>
                        <div>
                            <input type="reset"class="btn btn-warning" value="リセット">
                        </div>
                    </div>
                    <input type="hidden" value="{{ $user->id }}" name="user_id">
                </div>
                <div class="mt-5 text-start">
                    <a href="{{ route('expense.index') }}" class="btn bg-opacity-50 bg-secondary">一覧へ</a>
                </div>
            </form>
        </div>
    </x-layouts.expense-manager>
    <footer class="footer py-3 bg-opacity-50 bg-primary mt-4">
    <div class="container d-flex justify-content-around">
        <div>
            <a class="btn btn-primary" href="{{ route('expense.create') }}">支出追加</a>
        </div>
        <div>
            <a class="btn btn-primary" href="{{ route('goal_amount.index') }}">目標金額設定</a>
        </div>
        <div>
            <a class="btn btn-primary" href="{{ route('report.index') }}">レポート</a>
        </div>
    </div>
</footer>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            let tabLinks = document.querySelectorAll('.nav-link');
            tabLinks.forEach(function(tabLink) {
                tabLink.addEventListener('click', function(e) {
                    let formId = this.getAttribute('data-tab-id');
                    let tabId = this.id;
                    activateTab(tabId);
                    showForm(formId);
                });
            });
            // ローカルストレージから保存されたタブIDを取得
            let savedFormId = localStorage.getItem('selectedFormId');
            let savedTabId = localStorage.getItem('selectedTabId');
            
            // 初回の場合や保存されたタブIDが見つからない場合はデフォルトのタブを表示する
            if (!savedFormId && !savedTabId) {
                savedFormId = "add_expense_form";
                savedTabId = "expense_tab";
            }

            activateTab(savedTabId);
            showForm(savedFormId);
        })

        function activateTab(tabId){
            // 全てのタブを非active状態にする
            let tabs = document.querySelectorAll('.nav-link');
            tabs.forEach(function(tab){
                // console.log(tab);
                tab.classList.remove('active', 'bg-secondary', 'bg-opacity-50');
                tab.removeAttribute('aria-current');
            });

            // 選択されたタブをactive状態に
            let selectedTab = document.getElementById(tabId);
            console.log(tabId);
            if(selectedTab) {
                selectedTab.classList.add('active', 'bg-secondary', 'bg-opacity-50');
                selectedTab.setAttribute('aria-current', 'page');

                // 選択されたタブIDをローカルストレージに保存
                localStorage.setItem('selectedTabId', tabId);
            }
        }
    
        function showForm(formId) {
            //全てのタブコンテンツを非表示にする
            let formContents = document.querySelectorAll('form');
            formContents.forEach(function(form) {
                form.classList.add('d-none');
            });

            // 選択されたタブコンテンツを表示する
            let selectedForm = document.getElementById(formId);
            if (selectedForm) {
                selectedForm.classList.remove('d-none');

                // 選択されたタブIDをローカルストレージに保存
                localStorage.setItem('selectedFormId', formId);
            }

       
    }  
</script>
</body>
</html>