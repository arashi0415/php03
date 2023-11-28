<?php
//【重要】
//insert.phpを修正（関数化）してからselect.phpを開く！！
// include("funcs.php");
// $pdo = db_conn();
try {
  $db_name = "gs_db";     //データベース名
  $db_id   = "root";      //アカウント名
  $db_pw   = "";          //パスワード：XAMPPはパスワード無しに修正してください。
  $db_host = "localhost"; //DBホスト
  $pdo = new PDO('mysql:dbname='.$db_name.';charset=utf8;host='.$db_host, $db_id, $db_pw);
} catch (PDOException $e) {
  exit('DB Connection Error:'.$e->getMessage());
}

//２．データ登録SQL作成
$sql = "SELECT * FROM gs_an_table";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

//３．データ表示
$values = "";
if($status==false) {
  $error = $stmt->errorInfo();
  exit("SQLError:".$error[2]);
}

//全データ取得
$values =  $stmt->fetchAll(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC[カラム名のみで取得できるモード]
$json = json_encode($values,JSON_UNESCAPED_UNICODE);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>フリーアンケート表示</title>
<link rel="stylesheet" href="css/range.css">
<link href="css/bootstrap.min.css" rel="stylesheet">
<style>div{padding: 10px;font-size:16px;}</style>
</head>
<body id="main">
<!-- Head[Start] -->
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
      <a class="navbar-brand" href="index.php">データ登録</a>
      </div>
    </div>
  </nav>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<div>
    <div class="container jumbotron">

      <table>
      <?php foreach($values as $v){ ?>
        <tr>
          <td><?=$v["id"]?></td>
          <td><a href="detail.php?id=<?=$v["id"]?>"><?=$v["name"]?></a></td>
          <td><a href="delete.php?id=<?=$v["id"]?>">[削除]</a></td>
        </tr>
      <?php } ?>
      </table>

  </div>
</div>
<!-- Main[End] -->

<script>
  const a = '<?php echo $json; ?>';
  console.log(JSON.parse(a));
</script>
</body>
</html>







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
    echo '認証成功';
} else {
    echo '認証失敗';
    // エラーを表示する
    echo '<pre>';
    var_dump($stmt2->errorInfo());
    echo '</pre>';
}

//４．データ登録処理後
if ($status == false) {
    //*** function化する！*****************
    sql_error($stmt);
} else {
    //*** function化する！*****************
    redirect("index.php");
}