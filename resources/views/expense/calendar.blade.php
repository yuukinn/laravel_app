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
    <!-- カレンダー -->
    <div class="container calendar">
    </div>
    </x-layouts.expense-manager>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        const amounts = @json($amounts);
        
        // 曜日の配列を作成
        const weeks = ['日','月', '火', '水', '木', '金', '土'];

        // 現在の日時取得
        let dt = new Date();

        // 年取得
        let year = dt.getFullYear();

        // 月取得
        let month = dt.getMonth() + 1;

        // カレンダー表示処理
        function showCalendar(year, month, data) {
            let calendar = createCalendar(year, month, data);
            document.querySelector('.calendar').innerHTML = calendar;
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
            // calendarHtml += '<div class="table-responsive">';
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
            
                    if (startOfMonth.getYear() == dt.getYear() && startOfMonth.getMonth() == dt.getMonth() && startOfMonth.getDate() == dt.getDate()){
                    calendarHtml += '<td class="text-center calendar-col pe-1">'  + '<p class="m-0 today-color">' + startOfMonth.getDate() +  '</p>' +
                                     checkDate(startOfMonth.toLocaleDateString("js-JP", {year: "numeric", month: "2-digit", day: "2-digit"}).replaceAll('/', '-'), data); +  
                                    '</td>';
                    } else {
                    calendarHtml += '<td class="text-center calendar-col pe-1">'  + '<p class="m-0">' + startOfMonth.getDate() +  '</p>' +
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
                    showCalendar(year, month, res);
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
                    return '<span class="amount-text">' + "￥" + data[j]['date_amount'] + '</span>';
                }
            }
            return '<br><span>' + "" + '</span>';
        }
        
        document.getElementById('next_month').addEventListener('click', moveCalendar);
        document.getElementById('last_month').addEventListener('click', moveCalendar);

        // カレンダー表示
        showCalendar(year, month, amounts); 

    </script>
</body>
</html>