<?php
require_once 'connect.php';

$conn = connectDatabase();

echo json_encode([
    "success" => true,
    "message" => "Kết nối database thành công!"
], JSON_UNESCAPED_UNICODE);

// Đóng kết nối (tùy chọn)
mysqli_close($conn);
?>
