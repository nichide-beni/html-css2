<?php
$name = '';
$email = '';
$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 入力値を変数に入れる

  // データがないときにエラーを出してみよう
  // 例: お名前を入力してください
  if (!$name) {
  }

  if (empty($errors)) {
    // エラーがなければ確認画面へ
    session_start(); // セッションを使うことで、ページ遷移しても値を保持できる
    $_SESSION['form'] = [
      'name' => $name,
      'email' => $email,
      'password' => $password,
      'message' => $message,
    ];
    header('Location: ./confirm.php');
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>お問い合わせフォーム</title>
  <link rel="stylesheet" href="./css/style.css">
</head>
<body>
  <h1>お問い合わせフォーム</h1>
  <form method="post" action="">
    <label>お名前：
      <input type="text" name="name" value="<?php htmlspecialchars($name) ?>">
      <?php if (isset($errors['name'])): ?>
        <div class="error"><?php $errors['name'] ?></div>
      <?php endif; ?>
    </label>

    <!-- メールアドレス name:email -->

    <!-- パスワード name:password -->

    <!-- お問い合わせ内容 name:message, テキストエリアで記述, 改行は考慮しなくていい -->

    <button type="submit">確認画面へ</button>
  </form>
</body>
</html>
