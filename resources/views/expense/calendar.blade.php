<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@latest/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <title>Document</title>
</head>
<body class="base">
    <x-layouts.expense-manager>

    <div class="text-center my-4"> 
        <button class="month-btn btn-lg" id="last_month"><<</button>
        <span class="expense-date"></span>
        <button class="month-btn btn-lg" id="next_month">>></button>
    </div>

    <!-- 収支 -->
    <div class="my-3">
        <div class="d-flex justify-content-evenly">
            <div>
                <p class="item mb-0 text-center">収入</p>
                <h3 class="income"></h3>
            </div>
            <div class="d-flex align-items-center pt-3">
                <h3>-</h3>
            </div>
            <div>
                <p class="item mb-0 text-center">支出</p>
                <h3 class="expense"></h3>
            </div>
            <div class="d-flex align-items-center pt-3">
                <h3 >=</h3>
            </div>
            <div>
                <p class="item mb-0 text-center">収支</p>
                <h3 class="incomeAndExpense"></h3>
            </div>
        </div>
    </div>
   
    <!-- カレンダー -->
    <div class="container calendar">
    </div>

    <!--天気 -->
    <div class="container">
        @for($i = 0; $i < count($temperature); $i++ )
        @if($temperature[$i]['date'] == $currentDate->format('Y-m-d'))
        <div class="d-flex justify-content-between align-items-center border-bottom" style=" background-color: #a3f8b5;">
            <p class="mb-0 temperature-date">{{ $temperature[$i]['date'] }}</p>
            <img src="{{ $temperature[$i]['day']['condition']['icon'] }}" alt="Weather Icon" class="img-fluid mb-2" style="max-width: 50px; max-height: 50px;">
            <div class="d-flex">
                <label class="me-2">降水確率</label>
                <p class="mb-0">{{ $temperature[$i]['day']['daily_chance_of_rain']}}%</p>
            </div>
        </div>
        @else
        <div class="d-flex justify-content-between align-items-center border-bottom">
            <p class="mb-0 temperature-date">{{ $temperature[$i]['date'] }}</p>
            <img src="{{ $temperature[$i]['day']['condition']['icon'] }}" alt="Weather Icon" class="img-fluid mb-2" style="max-width: 50px; max-height: 50px;">
            <div class="d-flex">
                <label class="me-2">降水確率</label>
                <p class="mb-0">{{ $temperature[$i]['day']['daily_chance_of_rain']}}%</p>
            </div>
        </div>
        @endif
        @endfor
</div>
    </x-layouts.expense-manager>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        const amounts = @json($amounts);

        const income = @json($incomeSum);
        
        const expense = @json($sum);

        const incomeAndExpense = @json($incomeAndExpense);

        // 曜日の配列を作成
        const weeks = ['日','月', '火', '水', '木', '金', '土'];

        // 現在の日時取得
        let dt = new Date();

        // 年取得
        let year = dt.getFullYear();

        // 月取得
        let month = dt.getMonth() + 1;

        // カレンダー表示処理
        function showCalendar(year, month, data, income, expense, incomeAndExpense) {
            let calendar = createCalendar(year, month, data);
            document.querySelector('.calendar').innerHTML = calendar;

            // 収入
            document.querySelector('.income').innerHTML = "￥" + Number(income).toLocaleString();
            // 支出
            document.querySelector('.expense').innerHTML = "￥" +  Number(expense).toLocaleString();
            // 収支
            if (incomeAndExpense > 0){
                document.querySelector('.incomeAndExpense').classList.add('text-primary');
                document.querySelector('.incomeAndExpense').innerHTML = "￥" + incomeAndExpense.toLocaleString();
            } else {
                document.querySelector('.incomeAndExpense').classList.add('text-danger');
                document.querySelector('.incomeAndExpense').innerHTML = "￥" + incomeAndExpense.toLocaleString();
            }
        }

        // カレンダーの土台作成
        function createCalendar(year, month, data){
            // 月初めの日付を取得
            let startOfMonth = new Date(year, month -1 , 1);

            // 来月の月初の日付を取得
            const nextStartOfDate = new Date(year, month, 1);

            // 今月末の日付取得
            let endOfMonth = new Date(year, month, 0);
            let endOfdate = endOfMonth.getDate();

            // 月初の曜日を取得
            let startOfWeek = startOfMonth.getDay();

            document.querySelector('.expense-date').innerHTML = year + '年' + month + '月';

            calendarHtml = '<table class="table-responsive w-100">';
            // 曜日列作成
            calendarHtml += '<thead>'
            weeks.forEach(function(week){
                calendarHtml += '<th class="text-center header">' + week + '</th>';
            });
            calendarHtml += '</thead>';

            // 日付列作成
            calendarHtml += '<tbody>';
            for (let w = 0; w  < 6; w++ ) {
                calendarHtml += '<tr>';
                for (let i = 0; i < 7; i++) {

                    if (w == 0 && startOfMonth.getDay() != i) {
                        calendarHtml += '<td>' +  '</td>'
                        continue;
                    }

                    if (startOfMonth.getMonth() == nextStartOfDate.getMonth()) {
                        console.log("d")
                        calendarHtml += '<td class="text-center calendar-col pe-1">'  + '<p class="date m-0">' + startOfMonth.getDate() + '</p>'  + '<br>' + 
                                    '</td>';
                        // 1日進める処理
                        startOfMonth.setDate(startOfMonth.getDate() + 1);
                        continue;
                    }
                    
                    var url = "{{ route('expense.create', ['date' => ':date']) }}";
                        url = url.replace(':date', startOfMonth.getFullYear() + "-" + checkMonth(startOfMonth.getMonth()) + "-" + startOfMonth.getDate());
                        console.log(url);
                        console.log(startOfMonth);
                    if (startOfMonth.getYear() == dt.getYear() && startOfMonth.getMonth() == dt.getMonth() && startOfMonth.getDate() == dt.getDate()){
                        calendarHtml += '<td class="text-center calendar-col pe-1">' + '<p class="m-0 today-color">' + '<a class="cal-date" href="' + url + '">' + startOfMonth.getDate() + '<a>' +  '</p>' +
                                     checkDate(startOfMonth.toLocaleDateString("js-JP", {year: "numeric", month: "2-digit", day: "2-digit"}).replaceAll('/', '-'), data); +  
                                    '</td>';
                    } else {
                    calendarHtml += '<td class="text-center calendar-col pe-1">'  + '<p class="m-0">'+ '<a class="cal-date" href="' + url + '">' + startOfMonth.getDate() +  '</p>' +
                                     checkDate(startOfMonth.toLocaleDateString("js-JP", {year: "numeric", month: "2-digit", day: "2-digit"}).replaceAll('/', '-'), data); + 
                                    '</td>';
                    }
                   

                    // 1日進める処理
                    startOfMonth.setDate(startOfMonth.getDate() + 1);

                }
                calendarHtml += '</tr>';
            }
            calendarHtml += '</tbody>';
            calendarHtml += '</table>';
            return calendarHtml;
        }

        // カレンダー日めくり処理
        function moveCalendar(e) {

            // 1ヶ月戻す処理
            if (e.target.id == 'last_month'){
                month --;

                if (month < 1){
                    year--;
                    month = 12;
                }
            }

            // 1ヶ月先送りする処理
            if (e.target.id == 'next_month'){
                month ++;

                if (month > 12) {
                    year++
                    month = 1;
                }
            }
            // Ajaxリクエスト送信
            $.ajax({
                url:'/expense/calendar/detail',
                method:'GET',
                data:{year:year, month:month},
                dataType: "json",
                contentType: 'application/json',
                success: function(res){
                    // res[0]:1ヶ月の支出データ
                    // res[1]:income
                    // res[2]:expense
                    // res[3]:incomeAndExpense
                    // res[4]:1ヶ月の収入データ
                    showCalendar(year, month, res[0], res[1], res[2], res[3]);
                },
                error: function (xhr, status, error){
                    console.error('Ajaxデータ送信エラー:', error);
                }
            })
        }

        // 日付ごとの支出額に調整する
        function checkDate(date, data){
            for (let j = 0; j < data.length; j++){
                if (date == data[j]['date']){
                    return '<span class="amount-text">' + "￥" + Number(data[j]['date_amount']).toLocaleString() + '</span>';
                } 
            }
            return '<br><span>' + "" + '</span>';
        }
        
        document.getElementById('next_month').addEventListener('click', moveCalendar);
        document.getElementById('last_month').addEventListener('click', moveCalendar);

        // カレンダー表示
        showCalendar(year, month, amounts, income, expense, incomeAndExpense); 

        function formatDate(dateString){
            let date = new Date(dateString);
            let temperatureMonth = date.getMonth() + 1;
            let temperatureDate = date.getDate();
            return temperatureMonth + "/" + temperatureDate;
        }

        let temperatureDateList = document.querySelectorAll('.temperature-date');
        temperatureDateList.forEach(function (temperatureDate) {
            let dateString = temperatureDate.textContent;
            let formattedDate = formatDate(dateString);
            temperatureDate.textContent = formattedDate;
        })           

        function checkMonth(month){
            return month < 10 ? '0' + month : month.toString();
        }
    </script>
</body>
</html>