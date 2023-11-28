<?php

include("funcs.php");
$pdo = db_conn();

$tableId = isset($_GET['id']) ? htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8') : null;
var_dump($tableId);
// テーブル名の妥当性を確認
if (!preg_match('/^[0-9a-zA-Z_]+$/', $tableId)) {
    die('無効なテーブル名です。');
}

// 実際のテーブル名を構築
$tableName = 'talk_table' . $tableId;

// テーブルを作成するクエリ
$sql = "CREATE TABLE $tableName (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    talk VARCHAR(255) NOT NULL,
    indate DATE NOT NULL
)";

// クエリを実行してテーブルを作成
if ($pdo->query($sql)) {
    echo "テーブルが正常に作成されました";
} else {
    echo "テーブルの作成に失敗しました: " . $pdo->errorInfo()[2];
}
?>

