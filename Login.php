<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
require_once 'connect.php';

$conn = connectDatabase();

$data = json_decode(file_get_contents("php://input"), true);
$identifier = $data['identifier'];
$password = $data['password'];

if (empty($identifier) || empty($password)) {
    echo json_encode(["error" => "Thiếu thông tin đăng nhập"], JSON_UNESCAPED_UNICODE);
    exit();
}

$sql = "SELECT ID, EMAIL, USERNAME FROM USERS WHERE EMAIL = ? AND PASS = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $identifier, $password);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    echo json_encode(["error" => "Lỗi truy vấn CSDL", "details" => mysqli_error($conn)], JSON_UNESCAPED_UNICODE);
    exit();
}

$user = mysqli_fetch_assoc($result);
if (!$user) {
    echo json_encode(["error" => "Sai tài khoản hoặc mật khẩu"], JSON_UNESCAPED_UNICODE);
    exit();
}

echo json_encode(["message" => "Đăng nhập thành công", "user" => $user], JSON_UNESCAPED_UNICODE);
mysqli_close($conn);

?>
