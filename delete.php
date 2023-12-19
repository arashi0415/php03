<?php
//1. POSTデータ取得
ini_set("display_errors", 1);
error_reporting(E_ALL);

session_start();

$id2 = $_GET["id"];
$filename = $_GET['filename'];


preg_match('/\d+/', $filename, $matches);

//2. DB接続します
include("funcs.php");
$pdo = db_conn();


// 正規表現で数字を抽出

$number = intval($matches[0]);
$id = $_SESSION['id'];
//３．データ登録SQL作成
$commonIdentifier = 'talk_table';

// 実際のテーブル名を構築
$tableName1 = $commonIdentifier . $number . "_" . $id;
$tableName2 = $commonIdentifier . $id . "_" . $number;

$checkTableQuery1 = "SHOW TABLES LIKE '" . $tableName1 . "'";
$checkTableQuery2 = "SHOW TABLES LIKE '" . $tableName2 . "'";

$stmtCheckTable1 = $pdo->query($checkTableQuery1);
$stmtCheckTable2 = $pdo->query($checkTableQuery2);

if ($stmtCheckTable2->rowCount() === 0) {
    $stmt = $pdo->prepare("DELETE FROM `$tableName1` WHERE id=:id");
    $stmt->bindValue(':id', $id2, PDO::PARAM_INT);
    $status = $stmt->execute();
} else {
    $stmt = $pdo->prepare("DELETE FROM `$tableName2` WHERE id=:id");
    $stmt->bindValue(':id', $id2, PDO::PARAM_INT);
    $status = $stmt->execute();
}

//４．データ登録処理後
if($status==false){
  sql_error($stmt);
}else{
  redirect("$filename");
}
?>
