<?php
$name = '';
$email = '';
$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
  // 改行コード対策、注意
  $message = filter_input(INPUT_POST, 'message', FILTER_UNSAFE_RAW);

  if (!$name) {
    $errors['name'] = 'お名前を入力してください。';
  }
  if (!$email) {
    $errors['email'] = '有効なメールアドレスを入力してください。';
  }
  if (!$password) {
    $errors['password'] = 'パスワードを入力してください。';
  }
  if (!$message) {
    $errors['message'] = 'お問い合わせ内容を入力してください。';
  }

  if (empty($errors)) {
    // エラーがなければ確認画面へ
    session_start();
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
        <div class="error"><?php echo $errors['name'] ?></div>
      <?php endif; ?>
    </label>

    <label>メールアドレス：
      <input type="email" name="email" value="<?php htmlspecialchars($email) ?>">
      <?php if (isset($errors['email'])): ?>
        <div class="error"><?php echo $errors['email'] ?></div>
      <?php endif; ?>
    </label>

    <label>パスワード：
      <input type="password" name="password" value="">
      <?php if (isset($errors['password'])): ?>
        <div class="error"><?php echo $errors['password'] ?></div>
      <?php endif; ?>
    </label>

    <label>お問い合わせ内容：
      <textarea name="message" rows="5"><?php nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) ?></textarea>
      <?php if (isset($errors['message'])): ?>
        <div class="error"><?php echo $errors['message'] ?></div>
      <?php endif; ?>
    </label>

    <button type="submit">確認画面へ</button>
  </form>
</body>
</html>
