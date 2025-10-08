<?php
// isset, $_GET, $_POSTなどはPHPの仕様で存在する
$getVal = isset($_GET['val1']) ? $_GET['val1'] : '未設定';
$postVal = isset($_POST['val2']) ? $_POST['val2'] : '未設定';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>サンプル</title>
  <link rel="stylesheet" href="./css/style.css">
</head>
<body>
  <h1>サンプル</h1>
  <form method="post" action="">
    <div>GETパラメーター: <?php echo $getVal ?></div>
    <div>POSTパラメーター: <?php echo $postVal ?></div>
    <input type="text" value="" name="val2">
    <input type="submit" value="送信"va>
  </form>
</body>
</html>
