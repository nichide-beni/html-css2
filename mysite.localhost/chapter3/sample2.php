<?php
require_once 'db_config.php';
$pdo = connect_db();

session_start();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$name = '';
$content = '';
$is_edit = false;
$error_name = '';
$error_content = '';

// セッションからエラーと入力値を取得
if (isset($_SESSION['form_data'])) {
  $name = $_SESSION['form_data']['name'] ?? '';
  $content = $_SESSION['form_data']['content'] ?? '';
  $error_name = $_SESSION['form_data']['error_name'] ?? '';
  $error_content = $_SESSION['form_data']['error_content'] ?? '';
  unset($_SESSION['form_data']);
}

// 編集モード判定（セッションがない場合のみDBから取得）
if ($id !== false && $id > 0 && $name === '' && $content === '') {
  $stmt = $pdo->prepare("SELECT name, content FROM chat_history WHERE id = ?");
  $stmt->execute([$id]);
  $chat = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($chat) {
    $name = $chat['name'];
    $content = $chat['content'];
    $is_edit = true;
  }
} elseif ($id !== false && $id > 0) {
  $is_edit = true;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title><?php echo $is_edit ? '投稿を編集' : '新規投稿'; ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1><?php echo $is_edit ? '投稿を編集' : '新規投稿'; ?></h1>

  <form method="post" action="sample3.php">
    <?php if ($is_edit): ?>
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
    <?php endif; ?>

    <label for="name">名前</label>
    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>" required>
    <?php if ($error_name): ?>
      <div class="error"><?php echo htmlspecialchars($error_name); ?></div>
    <?php endif; ?>

    <label for="content">内容</label>
    <textarea name="content" id="content" rows="5" required><?php echo htmlspecialchars($content); ?></textarea>
    <?php if ($error_content): ?>
      <div class="error"><?php echo htmlspecialchars($error_content); ?></div>
    <?php endif; ?>

    <input type="submit" value="<?php echo $is_edit ? '更新する' : '投稿する'; ?>" class="btn">
  </form>
</body>
</html>
