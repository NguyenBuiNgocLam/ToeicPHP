<?php
header("Content-Type: application/json; charset=UTF-8");
require_once 'C:/xampp/htdocs/testtoeic/connect.php';

$conn = connectDatabase();

$ID_USER = isset($_GET['ID_USER']) ? intval($_GET['ID_USER']) : 0;
$ID_LOAI = isset($_GET['ID_LOAI']) ? intval($_GET['ID_LOAI']) : 0;
$ID_LOAI_BAI = isset($_GET['ID_LOAI_BAI']) ? intval($_GET['ID_LOAI_BAI']) : 0;
$ID_STT_QUESTION = isset($_GET['ID_STT_QUESTION']) ? intval($_GET['ID_STT_QUESTION']) : 0;

if ($ID_USER === 0) {
    echo json_encode(["error" => "ID_USER is required"], JSON_UNESCAPED_UNICODE);
    exit();
}
if ($ID_LOAI === 0) {
    echo json_encode(["error" => "ID_LOAI is required"], JSON_UNESCAPED_UNICODE);
    exit();
}

// Lấy thông tin CacLoaiBai
$sql = "SELECT ID_LOAI, TOTAL_QUESTION, INSTRUCT, TITLE FROM CacLoaiBai WHERE ID_LOAI = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $ID_LOAI);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($row) {
    // Lấy kết quả Result
    $sql_result = "SELECT NUMBER_DONE, NUMBER_RIGHT FROM Result WHERE ID_LOAI = ? AND ID_USER = ?";
    $stmt_result = mysqli_prepare($conn, $sql_result);
    mysqli_stmt_bind_param($stmt_result, "ii", $ID_LOAI, $ID_USER);
    mysqli_stmt_execute($stmt_result);
    $result_result = mysqli_stmt_get_result($stmt_result);
    $res = mysqli_fetch_assoc($result_result);
    $row['NUMBER_DONE'] = $res ? $res['NUMBER_DONE'] : 0;
    $row['NUMBER_RIGHT'] = $res ? $res['NUMBER_RIGHT'] : 0;

    // Lấy các câu hỏi cho từng bảng QuestionP1...QuestionP9
    $questions = [];
    for ($i = 1; $i <= 9; $i++) {
        $table = "QuestionP" . $i;
        $sql_q = "SELECT * FROM $table WHERE ID_LOAI = ? AND ID_LOAI_BAI = ?";
        if ($i <= 7) {
            $sql_q .= " AND ID_STT_QUESTION = ?";
        }
        $stmt_q = mysqli_prepare($conn, $sql_q);
        if ($i <= 7) {
            mysqli_stmt_bind_param($stmt_q, "iii", $ID_LOAI, $ID_LOAI_BAI, $ID_STT_QUESTION);
        } else {
            mysqli_stmt_bind_param($stmt_q, "ii", $ID_LOAI, $ID_LOAI_BAI);
        }
        mysqli_stmt_execute($stmt_q);
        $result_q = mysqli_stmt_get_result($stmt_q);
        $q_arr = [];
        while ($q = mysqli_fetch_assoc($result_q)) {
            $q_arr[] = $q;
        }
        $row[$table] = $q_arr;
    }
    echo json_encode(["data" => [$row]], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["error" => "Không có dữ liệu"], JSON_UNESCAPED_UNICODE);
}
mysqli_close($conn);
?>
