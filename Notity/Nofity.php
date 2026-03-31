<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'C:/xampp/htdocs/testtoeic/connect.php';

$conn = connectDatabase();

$sql = "SELECT * FROM notify";
$result = mysqli_query($conn, $sql);
$notify = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $notify[] = $row;
    }
}
echo json_encode(['notify' => $notify], JSON_UNESCAPED_UNICODE);
mysqli_free_result($result);
mysqli_close($conn);
?>