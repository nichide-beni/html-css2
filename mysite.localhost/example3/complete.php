<?php
session_start();
$form = $_SESSION['form'] ?? null;

if (!$form) {
  header('Location: ./index.php');
  exit;
}

// メール送信処理（SMTP認証なし）
$to = 'your@example.com';
$subject = 'お問い合わせがありました';
$body = <<<EOT
名前: {$form['name']}
メール: {$form['email']}
内容:
{$form['message']}
EOT;

$headers = "From: {$form['email']}";
mb_language("Japanese");
mb_internal_encoding("UTF-8");
#mb_send_mail($to, $subject, $body, $headers);

// セッション削除
unset($_SESSION['form']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>送信完了</title>
  <link rel="stylesheet" href="./css/style.css">
</head>
<body>
  <h1>送信完了</h1>
  <p>お問い合わせありがとうございました。内容を確認のうえ、折り返しご連絡いたします。</p>

  <?php var_dump($form); ?>
</body>
</html>
