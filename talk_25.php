<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

session_start();

include("funcs.php");
$pdo = db_conn();



$filename = basename(__FILE__);

// 正規表現で数字を抽出
preg_match('/\d+/', $filename, $matches);

// $matches[0] には文字列が格納されるので、数値に変換して変数に代入
$number = intval($matches[0]);

// テーブル名の妥当性を確認

// 実際のテーブル名を構築
$tableName = 'talk_table'.$number."_".$_SESSION['id'] ;
$id = $_SESSION['id'];

$commonIdentifier = 'talk_table';

// 実際のテーブル名を構築
$tableName1 = $commonIdentifier . $number . "_" . $id;
$tableName2 = $commonIdentifier . $id . "_" . $number;

// テーブルが存在するか確認するクエリ
$checkTableQuery1 = "SHOW TABLES LIKE '" . $tableName1 . "'";
$checkTableQuery2 = "SHOW TABLES LIKE '" . $tableName2 . "'";

$stmtCheckTable1 = $pdo->query($checkTableQuery1);
$stmtCheckTable2 = $pdo->query($checkTableQuery2);

echo "Result 1: " . $stmtCheckTable1->rowCount() . PHP_EOL;
echo "Result 2: " . $stmtCheckTable2->rowCount() . PHP_EOL;

// テーブルが存在するか確認する
if ($stmtCheckTable1->rowCount() === 0 && $stmtCheckTable2->rowCount() === 0) {
    // テーブルを作成するクエリ
    $createTableQuery = "CREATE TABLE IF NOT EXISTS `$tableName` (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        talk VARCHAR(255) NOT NULL,
        talkflag INT DEFAULT 0 CHECK (talkflag >= 0 AND talkflag <= 1),
        -- image VARCHAR(255) NOT NULL,
        indate DATE NOT NULL
    )";
   $stmtCreateTable = $pdo->prepare($createTableQuery);
   $stmtCreateTable->execute();
}
if ($stmtCheckTable2->rowCount() === 0) {
    $selectDataQuery = "SELECT * FROM `$tableName1`";
} else {
    $selectDataQuery = "SELECT * FROM `$tableName2`";
}

$stmtSelectData = $pdo->prepare($selectDataQuery);
$stmtSelectData->execute();
$values = $stmtSelectData->fetchAll(PDO::FETCH_ASSOC);



foreach ($values as $row) {
    // データの処理を行う（例：表示）
    echo "ID: " . $row['id'] . ", Name: " . $row['name'] . ", Talk: " . $row['talk'] . ", Date: " . $row['indate'] . PHP_EOL;
}
?>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        <?php 
        $value = 1;
        $userName = $_SESSION['user_name'];
        
        if ($stmtCheckTable2->rowCount() === 0) {
            $stmt = $pdo->prepare("UPDATE `$tableName1` SET talkflag = :value1 WHERE name != :userName");
        } else {
            $stmt = $pdo->prepare("UPDATE `$tableName2` SET talkflag = :value1 WHERE name != :userName");
        }
        
        $stmt->bindValue(':value1', $value, PDO::PARAM_INT);
        $stmt->bindValue(':userName', $userName, PDO::PARAM_STR);
        $status = $stmt->execute();
        ?>
    });

    
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h1>チャット</h1>
<div id="send chat"><table style="margin-left: 500px;">
    <?php foreach($values as $v){if ($v["name"] == $_SESSION['user_name']){ ?>
        <tr >
        <td><p><?=$v["indate"]?></p>
        <br>
        <p><?=$v["talk"]?></p>
        <p><?php if($v["talkflag"]==1){ echo "既読";} ?></p></td>
        <td><a href="delete.php?id=<?=$v["id"]?>&filename=<?=$filename?>">🗑️</a></td>
        </tr>
    <?php }} ?>
    </table></div>

    <div  id="receive chat"><table>
    <?php foreach($values as $v){if ($v["name"] != $_SESSION['user_name'] ){ ?>
        <tr >
            <td><p><?=$v["indate"]?></p>
            <br>
            <p><?=$v["talk"]?></p></td>
        </tr>
    <?php }} ?>
    </table></div>


<form id="chatF" method="POST" action="msg.php" >
    <input type="text" name="talk" id="talkF" cols="100" rows="10" style="height: 25px;">
    <input type="hidden" name="filename" value="<?= $filename ?>">
    <!-- <input type="file" name="image" accept="image/*"> -->
    <input type="submit" value="送信">
</form>




</body>
</html>