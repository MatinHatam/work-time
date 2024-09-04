<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>محاسبه ساعت کاری</title>
    <style>
        table {
            width: 50%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            text-align: center;
            padding: 8px;
        }

        .time {
            padding: 5px 20px;
            border-radius: 15px;
            border-color: green;
            font-size: 25px;
        }

        #process {
            background-color: green;
            padding: 10px 40px;
            color: white;
            border-radius: 10px;
            border: none;

        }

        #process:hover {
            background-color: #014d01;
        }

        #total-time {
            background-color: #d3c911;
            padding: 10px 40px;
            color: #000000;
            border-radius: 10px;
            border: none;
        }
        #total-time :hover{
            background-color: #706b05;
        }

        #reset {
            background-color: red;
            padding: 10px;
            color: white;
            border-radius: 10px;
        }
        #reset :hover{
            background-color: #b70707;
        }
        #del{
            background-color: red;
            color: white;
            padding: 5px 15px 5px 15px;
            border-radius: 10px;
            border: none
        }
        #del :hover{
            background-color: #b70707;
        }
        .lab{
            font-size: 25px;
        }
    </style>
</head>
<body>
<h2 style="text-align: center;">محاسبه ساعت کاری</h2>
<form action="" method="post" style="text-align: center;">
    <label for="start_time" class="lab">زمان ورود:</label>
    <input type="time" class="time" name="start_time" required><br><br>
    <label for="end_time" class="lab">زمان خروج:</label>
    <input type="time" class="time" name="end_time" required><br><br>
    <input type="submit" id="process" value="محاسبه">
</form>

<br>
<form action="total_work_hours.php" method="post" style="text-align: center;">
    <input type="submit" id="total-time" value="محاسبه مجموع کل ساعت‌ها">
</form>

<br>
<?php
session_start(); // شروع session

$hourly_rate = 30000; // نرخ ساعتی

if (!isset($_SESSION['work_times'])) {
    $_SESSION['work_times'] = []; // آرایه برای ذخیره ساعت‌ها
}

// ریست کردن ساعت‌ها
if (isset($_POST['reset'])) {
    $_SESSION['work_times'] = [];
    echo "<p style='color: red; text-align: center;'>تمام ساعت‌ها ریست شدند!</p>";
}

// اضافه کردن ساعت جدید
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['reset'])) {
    if (isset($_POST['start_time']) && isset($_POST['end_time'])) {
        // دریافت زمان‌ها از فرم
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

        // تبدیل زمان‌ها به فرمت قابل محاسبه
        $start = strtotime($start_time);
        $end = strtotime($end_time);

        // محاسبه تفاوت زمان‌ها به ثانیه
        $diff_seconds = $end - $start;

        // تبدیل ثانیه‌ها به ساعت و دقیقه
        $hours = floor($diff_seconds / 3600);
        $minutes = floor(($diff_seconds % 3600) / 60);

        // ذخیره ساعت‌ها و دقیقه‌ها در آرایه session
        $_SESSION['work_times'][] = ['hours' => $hours, 'minutes' => $minutes];

        // نمایش نتیجه
        echo "<h1 style='text-align: center'>ساعت کاری محاسبه شده</h1>";
        echo "<h2 style='text-align: center'>جمع ساعت <br> $hours : $minutes</h2>";
    }
}

// نمایش لیست تمام زمان‌های ذخیره‌شده
if (!empty($_SESSION['work_times'])) {
    echo "<h2 style='text-align: center;'>لیست تمام زمان‌های ثبت‌شده:</h2>";
    echo "<table>";
    echo "<tr><th>ردیف</th><th>جمع ساعت</th><th>مبلغ حقوق</th><th>حذف</th></tr>";
    $row_number = 1;
    foreach ($_SESSION['work_times'] as $index => $time) {
        $salary = ($time['hours'] + $time['minutes'] / 60) * $hourly_rate;
        $salary_formatted = number_format($salary);
        echo "<tr><td>{$row_number}</td><td>{$time['hours']} : {$time['minutes']}</td><td>$salary_formatted تومان</td>
              <td><form action='' method='post'><input type='hidden' name='delete'  value='{$index}'><input type='submit' id='del' value='حذف'></form></td></tr>";
        $row_number++;
    }
    echo "</table>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $index = $_POST['delete'];
    if (isset($_SESSION['work_times'][$index])) {
        unset($_SESSION['work_times'][$index]);
        $_SESSION['work_times'] = array_values($_SESSION['work_times']); // ریست کردن ایندکس‌ها
        echo "<p style='color: red; text-align: center;'>ساعت حذف شد.</p>";
    }
}
?>

<br><br><br>
<form action="" method="post" style="text-align: center; ">
    <input type="submit" id="reset" name="reset" value="ریست کردن ساعت‌ها">
</form>
</body>
</html>
