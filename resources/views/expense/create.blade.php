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
            <div class="d-flex justify-content-between">
                <div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="formSelector" value="add_expense_form">
                        <label class="form-check-label" for="">
                            支出追加
                        </label>
                    </div>
                    <div class="form-check">
                         <input class="form-check-input" type="radio" name="formSelector" value="add_category_form">
                        <label class="form-check-label" for="">
                            カテゴリ追加
                        </label>
                    </div>
                </div>
                <div>
                     <a class="btn bg-opacity-50 bg-secondary" href="{{ route('expense.index') }}">一覧へ</a>
                </div>
            </div>
            <div class="container-md w-50 mt-5 mb-4 text-center">
                @if ($errors->any() || session('message'))
                    <x-error-messages :errors="$errors" />
                    {{session('message')}}
                @endif
            </div>
            <form id="add_expense_form" name="add_expense_form" class="mt-4" action='{{ route("expense.detail.store") }}' method='POST'>
                <h3>支出追加</h3>
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
                    <input class="form-control" type="date" name="date">
                </div>
                <div class="mb-3">
                    <div>
                        <label class="form-label"><span class="text-danger">※</span>資産タイプ</label>
                    </div>
                    <label class="form-label" for="consumption">消費</label>
                    <input class="form-check-input me-2" type="radio" id="consumption" name="asset_type" value="消費">

                    <label class="form-label" for="wastage">浪費</label>
                    <input class="form-check-input me-2" type="radio" id="wastage" name="asset_type" value="浪費">

                    <label for="investment">投資</label>
                    <input class="form-check-input me-2" type="radio" id="investment" name="asset_type" value="投資">

                </div>
                <input type="hidden" value="{{ $user->id }}" name="user_id">
                <input type="hidden" value="{{ $user->has_set_email }}" name="email_flag">
                <input type="reset" value="リセット" class="btn btn-warning">
                <input type="submit" value="追加" class="btn btn-primary">
            </form>
            <form  action="{{ route('expense.store') }}"   method="POST" id="add_category_form" name="add_category_form" class="container visually-hidden text-center">
                @csrf
                <h3>カテゴリ追加</h3>
                <div class="">
                    <label for="">カテゴリ</label>
                    <input type="text" name="category">
                    <input type="submit" class="btn btn-primary" value="追加">
                    <input type="reset"class="btn btn-warning" value="リセット">
                    <input type="hidden" value="{{ $user->id }}" name="user_id">
                </div>
            </form>
        </div>
    </x-layouts.expense-manager>
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            // ページが読み込まれた時にセッションストレージから選択されたフォームIDを取得
            let selectedFormId = sessionStorage.getItem('selectedFormId');

            // 全てのフォームを非表示にする
            let forms = document.querySelectorAll('form');
            forms.forEach(function(form){
                form.classList.add('visually-hidden');
            });

            // 選択されたフォームがある場合は表示する
            if (selectedFormId){
                let selectedForm = document.getElementById(selectedFormId);
                selectedForm.classList.remove('visually-hidden');
            }

            //ラジオボタンの変更時にフォームを切り替える
            let radios = document.querySelectorAll('input[name="formSelector"]');
            radios.forEach(function(radio){;

                //chanegeイベントの監視
                radio.addEventListener('change', function() {

                     //全てのフォームを非表示にする
                    let forms = document.querySelectorAll('form');
                    forms.forEach(function(form){
                        form.classList.add('visually-hidden');
                    });

                    //選択されたフォームを表示する
                    let selectedFormId = this.value;
                    let selectedForm = document.getElementById(selectedFormId);
                    selectedForm.classList.remove('visually-hidden');

                    // 選択されたフォームのIDをセッションストレージに保存
                    sessionStorage.setItem('selectedFormId', selectedFormId);
                });
            });
        })
    
        // $(document).ready(function() {
        //     $("#price").blur(function () {
        //         charChange($(this));
        //     });
            
        //     charChange = function(e) {
        //         let val = e.val();
        //         let han = val.replace(/[0-9]/g, function(s){
        //             return String.fromCharCode(s.charCodeAt(0)-65248)
        //         });

        //         if(val.match(/[0-9]/g)){
        //             $(e).val(han);
        //         }
        //     }

        // });

</script>
</body>
</html>