<?php
function connectDatabase() {
    $conn = mysqli_connect(
        "sql5.freesqldatabase.com",   // host
        "sql5821814",              // username
        "RaG36YxqYn",          // password
        "sql5821814"      // database
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
