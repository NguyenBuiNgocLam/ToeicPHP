<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'C:/xampp/htdocs/testtoeic/connect.php';

$conn = connectDatabase();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT ID, TITLE_TOPIC, NUMBER_WORD FROM Topic WHERE ID = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && $row = mysqli_fetch_assoc($result)) {
    $topic = $row;
    // Lấy danh sách từ vựng liên quan
    $sql_vocab = "SELECT WORD, TYPE_VOCABULARY, MEANING, EXAMPLE_VOCABULARY, IMAGE_VOCABULARY FROM Vocabulary WHERE TOPIC_ID = ?";
    $stmt_vocab = mysqli_prepare($conn, $sql_vocab);
    mysqli_stmt_bind_param($stmt_vocab, "i", $id);
    mysqli_stmt_execute($stmt_vocab);
    $result_vocab = mysqli_stmt_get_result($stmt_vocab);
    $vocabularies = [];
    while ($vocab = mysqli_fetch_assoc($result_vocab)) {
        $vocabularies[] = $vocab;
    }
    $topic['Vocabulary'] = $vocabularies;
    echo json_encode(["data" => [$topic]], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["error" => "Không có dữ liệu"], JSON_UNESCAPED_UNICODE);
}
mysqli_close($conn);
?>
