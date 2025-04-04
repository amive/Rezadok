<?php
$host = "dpg-cvo0rt24d50c739at8ig-a.frankfurt-postgres.render.com";
$username = "rezadok_user";
$password = "QSuIUPpwpqpCW5wZ3OxibvwbWL3qWEsQ";
$dbname = "rezadok";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}
?>
