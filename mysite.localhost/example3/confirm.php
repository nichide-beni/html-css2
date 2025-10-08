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
  <p><strong>お名前：</strong><br><?php echo htmlspecialchars($form['name']) ?></p>
  <p><strong>メールアドレス：</strong><br><?php echo htmlspecialchars($form['email']) ?></p>
  <p><strong>パスワード：</strong><br>●●●●●●●●</p>
  <p><strong>お問い合わせ内容：</strong><br><?php echo nl2br(htmlspecialchars($form['message'], ENT_QUOTES, 'UTF-8')) ?></p>

  <form action="complete.php" method="post">
    <button type="submit">送信する</button>
  </form>
  <form action="index.php" method="post">
    <button type="button" onclick="history.back();">戻って修正する</button>
  </form>
</body>
</html>
