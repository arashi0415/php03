<?php 
ini_set("display_errors", 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>データ登録</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>div{padding: 10px;font-size:16px;}</style>
</head>
<body>

<!-- Head[Start] -->
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
    <div class="navbar-header"><a class="navbar-brand" href="select.php">ログイン</a></div>
    </div>
  </nav>
</header>
<!-- Head[End] -->

<form method="POST" action="insert.php">
  <div class="jumbotron">
   <fieldset>
    <legend>アカウント</legend>
     <label>名前 or Email：<input type="text" name="passNm"></label><br>
     <label>password：<input type="password" name="pw"></label><br>
     <input type="submit" value="送信">
    </fieldset>
  </div>
</form>

<!-- Main[Start] -->
<form method="POST" action="insert.php">
  <div class="jumbotron">
   <fieldset>
    <legend>アカウントお持ちでない方</legend>
     <label>名前：<input type="text" name="name"></label><br>
     <label>Email：<input type="text" name="email"></label><br>
     <label>password：<input type="password" name="pw"></label><br>
     <input type="submit" value="送信">
    </fieldset>
  </div>
</form>
<!-- Main[End] -->


</body>
</html>
