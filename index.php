<?php
    // 現在日付取得
    $now = date("Y-n-j-D-F");
    list($nowYear, $nowMonth, $nowDay, $nowStrDate, $nowStrMonth) = split('[-]', $now);

    // カレンダーの日付リストを取得
    $year = $nowYear;
    $month = $nowMonth;
    $strMonth = $nowStrMonth;
    $dateList = getList($month, $year);

    // カレンダーの日付リストを取得
    function getList($month, $year) {

        $passingSunday = false;
        $passingLastdate = false;
        $isSatday = false;

        // 月初と月末の日付を取得する
        $firstDate = $year."/".$month."/1";
        $lastDate = date("t", strtotime($firstDate));

        // 月初を起点に[$count]日後の日付を取得する
        $count = -6;
        while (1) {

            $date = date("Y-n-j-D", strtotime("$firstDate $count day"));
            $dateArray = toArray($date);

            // 日曜日からリストに追加する
            $passingSunday = $passingSunday || ($dateArray["strDate"] === "Sun");
            if ($passingSunday) {
                $dateList[] = $dateArray;

                // 月末を通過したか判定する
                $passingLastdate = $passingLastdate 
                                        || ($count >= 0 && $dateArray["day"] === $lastDate);
                // 土曜日かどうかを判定する
                $isSatday = ($dateArray["strDate"] === "Sat");
            }

            // 月末を通過し、土曜日であったら処理を抜ける
            if ($passingLastdate && $isSatday) {
                break;
            }
            $count++;
        }
        return $dateList;
    }

    // 日付情報を配列に格納する
    function toArray($date) {
       $array = split('[-]', $date);
        return array(
            "year" => $array[0],
            "month" => $array[1],
            "day" => $array[2],
            "strDate" => $array[3]
        );
    }

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>Calendar</title>
</head>
<body>
    <header>
        <h1>Calendar</h1>
    </header>
    <div class="main">
        <table class="cal-table">
            <caption><?php
                echo $strMonth." ".$year;
            ?></caption>
            <thead>
                <tr class="column-title">
                    <th>sun</th>
                    <th>mon</th>
                    <th>tue</th>
                    <th>wed</th>
                    <th>thu</th>
                    <th>fri</th>
                    <th>sat</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($dateList as $key => $value):
                    // td要素の文字列を生成する
                    $class = "";
                    if ($value["year"] === $nowYear && $value["month"] === $nowMonth && $value["day"] === $nowDay) {
                        $class .= "today ";
                    }
                    if ($value["month"] != $month) {
                        $class .= "disable ";
                    }
                    $strTd = "<td class=\"".$class."\">";
                    $strTd .= $value["day"];
                    $strTd .= "</td>";

                    // 出力する
                    if ($key % 7 === 0) {
                        echo "<tr>";
                    }
                    echo $strTd;
                    if ($key % 7 === 6) {
                        echo "</tr>";
                    }
                endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>