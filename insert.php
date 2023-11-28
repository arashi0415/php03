<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

//1. POSTデータ取得
$name = $_POST["name"];
$email = $_POST["email"];
$passNm =  $_POST["passNm"];
$pass = $_POST["pw"];

// 2. DB接続
include("funcs.php");
$pdo = db_conn();

// 3. データ検証用SQL作成

// 5. データ登録SQL作成（新規アカウントの登録）
if (!empty($name) && !empty($email) && !empty($pass)) {
    // パスワードのハッシュ化
    $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

    // データベースに挿入
    $stmt = $pdo->prepare("INSERT INTO account(name, email, pass, indate) VALUES(:name, :email, :pass, sysdate())");
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':pass', $hashedPassword, PDO::PARAM_STR); // ハッシュ化したパスワードを保存
    $status = $stmt->execute();
}

// ユーザー情報の取得
$sql = "SELECT * FROM account WHERE name=:name OR email=:email";
$stmt2 = $pdo->prepare($sql);
$stmt2->bindParam(':name', $passNm, PDO::PARAM_STR);
$stmt2->bindParam(':email', $passNm, PDO::PARAM_STR);
$status = $stmt2->execute();

$val = $stmt2->fetch();

// ログインの確認
if ($val && password_verify($pass, $val['pass'])) {
    session_start();
    $_SESSION['user_name'] = $val['name'];
    header("Location: select.php");
    exit;
  }
//４．データ登録処理後
if ($status == false) {
    //*** function化する！*****************
    sql_error($stmt);
} else {
    //*** function化する！*****************
    redirect("index.php");
}
?>
