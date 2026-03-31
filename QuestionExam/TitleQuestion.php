<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'connect.php';

$conn = connectDatabase();
$ID_YEAR = isset($_GET['ID_YEAR']) ? intval($_GET['ID_YEAR']) : 0;

$sql = "SELECT * FROM TitleQuestion WHERE ID_YEAR = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $ID_YEAR);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$data = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    if (!empty($data)) {
        echo json_encode(["data" => $data], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(["error" => "Không có dữ liệu"], JSON_UNESCAPED_UNICODE);
    }
} else {
    echo json_encode(["error" => "Lỗi truy vấn SQL"], JSON_UNESCAPED_UNICODE);
}
mysqli_close($conn);
?>
