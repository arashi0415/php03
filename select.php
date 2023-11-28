<?php
//【重要】
//insert.phpを修正（関数化）してからselect.phpを開く！！
include("funcs.php");
$pdo = db_conn();

//２．データ登録SQL作成
$sql = "SELECT * FROM account";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

//３．データ表示
$values = "";
if($status==false) {
  sql_error($stmt);
}

//全データ取得
$values =  $stmt->fetchAll(PDO::FETCH_ASSOC); //PDO::FETCH_ASSOC[カラム名のみで取得できるモード]
$json = json_encode($values,JSON_UNESCAPED_UNICODE);

session_start(); // セッションを開始
// ... (funcs.phpやdb_conn()の読み込みなど)

// セッション変数からユーザー名を取得
$user_name = $_SESSION['user_name'];

// ... (データベースからのデータ取得など)

// ユーザー名を表示
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
      <a class="navbar-brand" href="index.php"><?php echo 'ようこそ、' . h($user_name) . 'さん！'?></a>
      </div>
    </div>
  </nav>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<div class="container jumbotron">
        <table>
        <?php foreach($values as $v){ ?>
        <tr>
          <td><a href="talk_<?=$v["id"]?>.php"><?=$v["name"]?></a></td>
        </tr>
      <?php } ?>
        </table>
    </div>
</div>

<?php
                // 詳細ページ用のPHPファイルを生成
                $id = h($v["id"]);
                $talkContent = file_get_contents("inc.php");
                file_put_contents("talk_{$id}.php", $talkContent);
                ?>
<!-- <div>
    <div class="container jumbotron">

      <table>
      <?php foreach($values as $v){ ?>
        <tr>
          <td><?=h($v["id"])?></td>
          <td><?=h($v["name"])?></td>
          <td><a href="detail.php?id=<?=h($v["id"])?><td><?=h($v["name"])?></td>">リンク</a></td>
          <td><a href="delete.php?id=<?=h($v["id"])?><td><?=h($v["name"])?></td>">リンク</a></td>
        </tr>
      <?php } ?>
      </table>

  </div>
</div> -->
<!-- Main[End] -->


<script>
  const a = '<?php echo $json; ?>';
  console.log(JSON.parse(a));
</script>
</body>
</html>
