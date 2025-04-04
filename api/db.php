<?php
$host = "dpg-cvo0rt24d50c739at8ig-a.frankfurt-postgres.render.com";
$port = "5432";
$dbname = "rezadok";
$user = "rezadok_user";
$pass = "QSuIUPpwpqpCW5wZ3OxibvwbWL3qWEsQ";

$conn_string = "host=$host port=$port dbname=$dbname user=$user password=$pass";

$conn = pg_connect($conn_string);

if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات");
}
?>
