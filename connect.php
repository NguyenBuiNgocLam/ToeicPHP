<?php
function connectDatabase() {
    $conn = mysqli_connect(
        "sql100.infinityfree.com",   // host
        "if0_41515072",              // username
        "Lamngochai1408",          // password
        "if0_41515072_database_flutter"      // database
    );

    if (!$conn) {
        die(json_encode([
            "error" => "Lỗi kết nối CSDL",
            "details" => mysqli_connect_error()
        ], JSON_UNESCAPED_UNICODE));
    }

    mysqli_set_charset($conn, "utf8mb4");

    return $conn;
}
?>