<?php
$DB_host = "127.0.0.1";
$DB_port = "3307";
$DB_user = "root";
$DB_pass = "";
$DB_name = "banking_internet";

$DB_con = null;

try {
    $DB_con = new PDO("mysql:host={$DB_host};port={$DB_port};dbname={$DB_name}", $DB_user, $DB_pass);
    $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Keep $DB_con as null and let the application handle it or show error
    error_log($e->getMessage());
}
