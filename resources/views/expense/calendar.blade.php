<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
    <title>Document</title>
</head>
<body>
    <div>
        <button class="month_btn" id="last_month">前の月</button>
        <button class="month_btn" id="next_month">次の月</button>
    </div>
    <div class="container calendar">
       
    </div>
    <div>
        <p>{{ $amounts }}</p>
    </div>
    <script>
        // 曜日の配列を作成
        const weeks = ['日','月', '火', '水', '木', '金', '土'];

        // 現在の日時取得
        let dt = new Date();

        // 年取得
        let year = dt.getFullYear();

        // 月取得
        let month = dt.getMonth() + 1;

        // カレンダー表示処理
        function showCalendar(year, month) {
            let calendar = createCalendar(year, month);
            document.querySelector('.calendar').innerHTML = calendar;
        }

        // カレンダーの土台作成
        function createCalendar(year, month){
            // 月初めの日付を取得
            let startOfMonth = new Date(year, month -1 , 1);

            // 今月末の日付取得
            let endOfMonth = new Date(year, month, 0);
            let endOfdate = endOfMonth.getDate();

            // 月初の曜日を取得
            let startOfWeek = startOfMonth.getDay();

            let calendarHtml = '<p>' + year + '年' + month + '月';
            calendarHtml += '<table class="table" border="1">';
            // 曜日列作成
            calendarHtml += '<thead>'
            weeks.forEach(function(week){
                calendarHtml += '<th class="header">' + week + '</th>';
            });
            calendarHtml += '</thead>';

            // 日付列作成
            calendarHtml += '<tbody><tr>';
            for (let i = 1; i <= endOfdate; i++) {
                if (i == 1 && startOfMonth.getDay() != 0) {
                    calendarHtml += '<td colspan=' + startOfWeek + '></td>'
                }
                // 日曜日で改行を入れる
                if (startOfMonth.getDay() == 0) {
                    calendarHtml += '</tr><tr>'
                }
                // 今日の日付の場合、緑にする
                if (startOfMonth.getDate() == dt.getDate()){
                    calendarHtml += '<td class="bg-success">' + i + '</td>';
                }else {
                    calendarHtml += '<td>' + i + '</td>';
                }

                startOfMonth.setDate(startOfMonth.getDate() + 1);
            }

            calendarHtml += '</tr></tbody>';

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
            fetch('/expense/calendar/?year=' + year + '&month=' + month, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);

                showCalendar(year, month);
            });
        }


        document.getElementById('next_month').addEventListener('click', moveCalendar);
        document.getElementById('last_month').addEventListener('click', moveCalendar);

        showCalendar(year, month); 

    </script>
</body>
</html>