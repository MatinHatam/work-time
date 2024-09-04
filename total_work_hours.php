<html>
<head>
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
    </style>
</head>
</html>
<?php
session_start(); // شروع session

$hourly_rate = 30000; // نرخ ساعتی

$total_hours = 0;
$total_minutes = 0;
$total_salary = 0;

// محاسبه مجموع ساعت‌ها و دقیقه‌ها
if (isset($_SESSION['work_times'])) {
    foreach ($_SESSION['work_times'] as $time) {
        $total_hours += $time['hours'];
        $total_minutes += $time['minutes'];
    }

    // تبدیل دقیقه‌ها به ساعت و دقیقه
    $total_hours += floor($total_minutes / 60);
    $total_minutes = $total_minutes % 60;

    // محاسبه مبلغ کل حقوق
    $total_salary = ($total_hours + $total_minutes / 60) * $hourly_rate;
}

$total_salary_formatted = number_format($total_salary);

echo "<h2 style='text-align: center;'>مجموع کل ساعت‌های کاری:</h2>";
echo "<table style='margin: 0 auto;'>";
echo "<tr><th>ردیف</th><th>جمع کل ساعت‌ها</th><th>مبلغ کل حقوق</th></tr>";
echo "<tr><td>1</td><td>$total_hours : $total_minutes</td><td>$total_salary_formatted تومان</td></tr>";
echo "</table>";

if (!empty($_SESSION['work_times'])) {
    echo "<h3 style='text-align: center;'>لیست تمام زمان‌های ثبت‌شده:</h3>";
    echo "<table style='margin: 0 auto;'>";
    echo "<tr><th>ردیف</th><th>جمع ساعت</th><th>مبلغ حقوق</th></tr>";
    $row_number = 1;
    foreach ($_SESSION['work_times'] as $time) {
        $salary = ($time['hours'] + $time['minutes'] / 60) * $hourly_rate;
        $salary_formatted = number_format($salary);
        echo "<tr><td>{$row_number}</td><td>{$time['hours']} : {$time['minutes']}</td><td>$salary_formatted تومان</td></tr>";
        $row_number++;
    }
    echo "</table>";
}
?>
