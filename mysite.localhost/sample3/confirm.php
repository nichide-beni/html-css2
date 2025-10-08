<?php
session_start();
$form = $_SESSION['form'] ?? null;

if (!$form) {
  header('Location: ./index.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>確認画面</title>
  <link rel="stylesheet" href="./css/style.css">
</head>
<body>
  <h1>入力内容の確認</h1>
  <!-- パスワードと同じように他の項目も前のページに合わせて表示する。パスワードはそのままでOK。 -->
  <p><strong>パスワード：</strong><br>●●●●●●●●</p>

  <form action="complete.php" method="post">
    <button type="submit">送信する</button>
  </form>
  <form action="index.php" method="post">
    <!-- confitmで値を取る場合は他の方法もある -->
    <button type="button" onclick="history.back();">戻って修正する</button>
  </form>
</body>
</html>
