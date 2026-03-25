<?php
    $dbuser = "root";
    $dbpass = "";
    $host = getenv('IB_DB_HOST') ?: "127.0.0.1:3307";
    $port = getenv('IB_DB_PORT');
    $db = getenv('IB_DB_NAME') ?: "banking_internet";

    $port = ($port !== false && $port !== null && $port !== '') ? (int)$port : 3307;
    $mysqli = new mysqli($host, $dbuser, $dbpass, $db, $port);
    if ($mysqli->connect_errno) {
        http_response_code(500);
        die("Database connection failed: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
    }
