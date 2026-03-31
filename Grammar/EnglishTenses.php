<?php
header("Content-Type: application/json; charset=UTF-8");

require_once 'C:/xampp/htdocs/toeicphp/connect.php';

$conn = connectDatabase();
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Lấy thông tin EnglishTenses
$sql = "SELECT ID, NAMETENSES, DEFINE, Loai FROM EnglishTenses WHERE ID = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if ($row) {
    $row['VerbType'] = [];
    $row['Examples'] = [];
    $row['Usage'] = [];
    $row['Keywords'] = [];
    // Lấy VerbType
    $sql_vt = "SELECT TYPE_WORD, REGULAR, TOBE FROM VerbType WHERE TENSES_ID = ?";
    $stmt_vt = mysqli_prepare($conn, $sql_vt);
    mysqli_stmt_bind_param($stmt_vt, "i", $id);
    mysqli_stmt_execute($stmt_vt);
    $result_vt = mysqli_stmt_get_result($stmt_vt);
    while ($vt = mysqli_fetch_assoc($result_vt)) {
        $row['VerbType'][] = $vt;
    }
    // Lấy Examples
    $sql_ex = "SELECT TYPE_WORD_EXAMPLE, REGULAR_EXAMPLE, TOBE_EXAMPLE FROM Examples WHERE TENSEN_ID = ?";
    $stmt_ex = mysqli_prepare($conn, $sql_ex);
    mysqli_stmt_bind_param($stmt_ex, "i", $id);
    mysqli_stmt_execute($stmt_ex);
    $result_ex = mysqli_stmt_get_result($stmt_ex);
    while ($ex = mysqli_fetch_assoc($result_ex)) {
        $row['Examples'][] = $ex;
    }
    // Lấy Usage
    $sql_us = "SELECT DESCRIPTION_DEFINE FROM Usage WHERE TENSES_ID = ?";
    $stmt_us = mysqli_prepare($conn, $sql_us);
    mysqli_stmt_bind_param($stmt_us, "i", $id);
    mysqli_stmt_execute($stmt_us);
    $result_us = mysqli_stmt_get_result($stmt_us);
    while ($us = mysqli_fetch_assoc($result_us)) {
        $row['Usage'][] = $us;
    }
    // Lấy Keywords
    $sql_kw = "SELECT KEYWORD FROM Keywords WHERE TENSES_ID = ?";
    $stmt_kw = mysqli_prepare($conn, $sql_kw);
    mysqli_stmt_bind_param($stmt_kw, "i", $id);
    mysqli_stmt_execute($stmt_kw);
    $result_kw = mysqli_stmt_get_result($stmt_kw);
    while ($kw = mysqli_fetch_assoc($result_kw)) {
        $row['Keywords'][] = $kw;
    }
    echo json_encode(["data" => [$row]], JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["error" => "Không có dữ liệu"], JSON_UNESCAPED_UNICODE);
}
mysqli_close($conn);
?>
