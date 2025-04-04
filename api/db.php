<?php
$host = "dpg-cvo0rt24d50c739at8ig-a.frankfurt-postgres.render.com";
$user = "rezadok_user";
$pass = "QSuIUPpwpqpCW5wZ3OxibvwbWL3qWEsQ";
$dbname = "rezadok";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}
?>