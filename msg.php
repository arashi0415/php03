<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
session_start();
//1. POSTデータ取得
$talk  = $_POST["talk"];

$filename = $_POST['filename'];
preg_match('/\d+/', $filename, $matches);
// $matches[0] には文字列が格納されるので、数値に変換して変数に代入
$number = intval($matches[0]);
$id = $_SESSION['id'];
//2. DB接続します
include("funcs.php");
$pdo = db_conn();

$commonIdentifier = 'talk_table';

// 実際のテーブル名を構築
$tableName1 = $commonIdentifier . $number . "_" . $id;
$tableName2 = $commonIdentifier . $id . "_" . $number;

$checkTableQuery1 = "SHOW TABLES LIKE '" . $tableName1 . "'";
$checkTableQuery2 = "SHOW TABLES LIKE '" . $tableName2 . "'";

$stmtCheckTable1 = $pdo->query($checkTableQuery1);
$stmtCheckTable2 = $pdo->query($checkTableQuery2);

if ($stmtCheckTable2->rowCount() === 0) {
    $stmt = $pdo->prepare("INSERT INTO `$tableName1` (name, talk, indate) VALUES (:name, :talk, sysdate())");
    $stmt->bindValue(':name', $_SESSION['user_name'], PDO::PARAM_STR);
    $stmt->bindValue(':talk', $talk, PDO::PARAM_STR);
    $status = $stmt->execute();
} else {
    $stmt = $pdo->prepare("INSERT INTO `$tableName2` (name, talk, indate) VALUES (:name, :talk, sysdate())");
    $stmt->bindValue(':name', $_SESSION['user_name'], PDO::PARAM_STR);
    $stmt->bindValue(':talk', $talk, PDO::PARAM_STR);
    $status = $stmt->execute();
}
var_dump($_POST["image"]);
if(isset($_POST["image"])) {
//   $target_dir = "uploads/";
//   if (!file_exists($target_dir)) {
//     mkdir($target_dir, 0777, true); // 第3引数をtrueにすると親ディレクトリも作成
// }
var_dump($_POST["image"]);
  // $target_file = $target_dir . basename($_FILES["image"]["name"]);
  // $uploadOk = 1;
  // $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

  // // 画像形式の確認
  // $image_info = getimagesize($_FILES["image"]["tmp_name"]);
  // if ($image_info === false) {
  //     echo "アップロードされたファイルが画像ではありません。";
  //     $uploadOk = 0;
  // }

  // if ($uploadOk == 1) {
  //     if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
  //         echo "ファイル " . basename($_FILES["image"]["name"]) . " がアップロードされました.";

  //         $file_name = basename($_FILES["image"]["name"]);
          

  //         if ($stmtCheckTable2->rowCount() === 0) {
  //             $stmt = $pdo->prepare("INSERT INTO `$tableName1` (image) VALUES (:file_name)");
  //         } else {
  //             $stmt = $pdo->prepare("INSERT INTO `$tableName2` (image) VALUES (:file_name)");
  //         }

  //         $stmt->bindValue(':file_name', $file_name, PDO::PARAM_STR);
  //         $stmt->execute();

  //         if ($stmt->rowCount() === 1) {
  //             echo "データベースにレコードが追加されました.";
  //         } else {
  //             echo "レコードの追加中にエラーが発生しました.";
  //         }
  //     } else {
  //         echo "申し訳ありませんが、ファイルのアップロード中にエラーが発生しました.";
  //     }
  // }
}

//４．データ登録処理後
if($status==false){
  sql_error($stmt);
}else{
  redirect("$filename");
}

?>