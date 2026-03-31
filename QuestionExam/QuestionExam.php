<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'connect.php';

$conn = connectDatabase();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy thông tin YearExam
$sql_year = "SELECT ID FROM YearExam WHERE ID = ?";
$stmt_year = mysqli_prepare($conn, $sql_year);
mysqli_stmt_bind_param($stmt_year, "i", $id);
mysqli_stmt_execute($stmt_year);
$result_year = mysqli_stmt_get_result($stmt_year);
$year = mysqli_fetch_assoc($result_year);

if ($year) {
    $data = [];
    $data['id'] = $year['ID'];
    // Lấy danh sách STT_Question
    $sql_stt = "SELECT ID, STT FROM STT_Question WHERE ID_YEAR = ?";
    $stmt_stt = mysqli_prepare($conn, $sql_stt);
    mysqli_stmt_bind_param($stmt_stt, "i", $id);
    mysqli_stmt_execute($stmt_stt);
    $result_stt = mysqli_stmt_get_result($stmt_stt);
    $questions = [];
    while ($stt = mysqli_fetch_assoc($result_stt)) {
        $q = [
            'sothutubai' => $stt['STT'],
            'sothutucacbai' => []
        ];
        // Lấy danh sách câu hỏi cho mỗi STT_Question
        $sql_q = "SELECT IMAGE_QUESTION AS image, AUDIO_QUESTION AS audio, CORRECT_ASW AS correctAnswer FROM QuestionExam WHERE ID_STT_QUESTION = ?";
        $stmt_q = mysqli_prepare($conn, $sql_q);
        mysqli_stmt_bind_param($stmt_q, "i", $stt['ID']);
        mysqli_stmt_execute($stmt_q);
        $result_q = mysqli_stmt_get_result($stmt_q);
        while ($row_q = mysqli_fetch_assoc($result_q)) {
            $q['sothutucacbai'][] = $row_q;
        }
        $questions[] = $q;
    }
    $data['question'] = $questions;
    echo json_encode(['data' => [$data]], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["error" => "Không có dữ liệu"], JSON_UNESCAPED_UNICODE);
}
mysqli_close($conn);
?>
