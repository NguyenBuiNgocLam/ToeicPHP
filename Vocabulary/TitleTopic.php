<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'C:/xampp/htdocs/testtoeic/connect.php';

$conn = connectDatabase();

$sql = "SELECT ID, TITLE_TOPIC, NUMBER_WORD FROM Topic";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(["error" => "Lỗi truy vấn SQL"], JSON_UNESCAPED_UNICODE);
    die();
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}
mysqli_free_result($result);
mysqli_close($conn);

echo json_encode(["data" => $data], JSON_UNESCAPED_UNICODE);
?>
