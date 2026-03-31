<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'connect.php';
$conn = connectDatabase();

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "Dữ liệu đầu vào không hợp lệ"]);
    exit();
}

$ID_USER = $data["ID_USER"];
$ID_LOAI = $data["ID_LOAI"];
$NUMBER_DONE = $data["NUMBER_DONE"];
$NUMBER_RIGHT = $data["NUMBER_RIGHT"];
$ID_LOAI_BAI = $data["ID_LOAI_BAI"];
$ID_STT_QUESTION = $data["ID_STT_QUESTION"];

// Kiểm tra ID hợp lệ
if ($ID_USER === 0 || $ID_LOAI === 0 || $ID_LOAI_BAI === 0 || $ID_STT_QUESTION === 0) {
    echo json_encode(["success" => false, "message" => "Thiếu thông tin bắt buộc"]);
    exit();
}

// Kiểm tra bản ghi đã tồn tại chưa
$sql_check = "SELECT ID FROM Result WHERE ID_USER = ? AND ID_LOAI = ? AND ID_LOAI_BAI = ? AND ID_STT_QUESTION = ?";
$stmt_check = mysqli_prepare($conn, $sql_check);
mysqli_stmt_bind_param($stmt_check, "iiii", $ID_USER, $ID_LOAI, $ID_LOAI_BAI, $ID_STT_QUESTION);
mysqli_stmt_execute($stmt_check);
$result_check = mysqli_stmt_get_result($stmt_check);

if ($result_check === false) {
    echo json_encode(["success" => false, "message" => "Lỗi kiểm tra dữ liệu", "error" => mysqli_error($conn)]);
    exit();
}

if (mysqli_num_rows($result_check) > 0) {
    // Nếu bản ghi đã tồn tại, thực hiện UPDATE
    $sql_update = "UPDATE Result SET NUMBER_DONE = ?, NUMBER_RIGHT = ? WHERE ID_USER = ? AND ID_LOAI = ? AND ID_LOAI_BAI = ? AND ID_STT_QUESTION = ?";
    $stmt_update = mysqli_prepare($conn, $sql_update);
    mysqli_stmt_bind_param($stmt_update, "iiiiii", $NUMBER_DONE, $NUMBER_RIGHT, $ID_USER, $ID_LOAI, $ID_LOAI_BAI, $ID_STT_QUESTION);
    $success = mysqli_stmt_execute($stmt_update);
    if ($success) {
        echo json_encode(["success" => true, "message" => "Cập nhật kết quả thành công"]);
    } else {
        echo json_encode(["success" => false, "message" => "Lỗi khi cập nhật dữ liệu", "error" => mysqli_error($conn)]);
    }
    mysqli_stmt_close($stmt_update);
} else {
    // Nếu bản ghi chưa tồn tại, thực hiện INSERT
    $sql_insert = "INSERT INTO Result (ID_USER, ID_LOAI, ID_LOAI_BAI, ID_STT_QUESTION, NUMBER_DONE, NUMBER_RIGHT) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_insert = mysqli_prepare($conn, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "iiiiii", $ID_USER, $ID_LOAI, $ID_LOAI_BAI, $ID_STT_QUESTION, $NUMBER_DONE, $NUMBER_RIGHT);
    $success = mysqli_stmt_execute($stmt_insert);
    if ($success) {
        echo json_encode(["success" => true, "message" => "Lưu kết quả thành công"]);
    } else {
        echo json_encode(["success" => false, "message" => "Lỗi khi lưu dữ liệu", "error" => mysqli_error($conn)]);
    }
    mysqli_stmt_close($stmt_insert);
}

mysqli_free_result($result_check);
mysqli_stmt_close($stmt_check);
mysqli_close($conn);
?>
