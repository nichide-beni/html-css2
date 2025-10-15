<?php
require_once 'db_config.php';
$pdo = connect_db();
session_start();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$name = trim(filter_input(INPUT_POST, 'name'));
$content = trim(filter_input(INPUT_POST, 'content'));

$error_name = '';
$error_content = '';

if ($name === '' || $name === null) {
  $error_name = '名前を入力してください。';
}
if ($content === '' || $content === null) {
  $error_content = '内容を入力してください。';
}

if ($error_name || $error_content) {
  $_SESSION['form_data'] = [
    'name' => $name,
    'content' => $content,
    'error_name' => $error_name,
    'error_content' => $error_content
  ];
  $redirect_url = 'edit.php';
  if ($id !== false && $id > 0) {
    $redirect_url .= '?id=' . urlencode($id);
  }
  header('Location: ' . $redirect_url);
  exit;
}

// 正常処理
$is_edit = false;
if ($id !== false && $id > 0) {
  $stmt = $pdo->prepare("UPDATE chat_history SET name = ?, content = ? WHERE id = ?");
  $stmt->execute([$name, $content, $id]);
  $is_edit = true;
} else {
  $stmt = $pdo->prepare("INSERT INTO chat_history (name, content) VALUES (?, ?)");
  $stmt->execute([$name, $content]);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>完了</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1><?php echo $is_edit ? '投稿を更新しました' : '新規投稿を追加しました'; ?></h1>
  <p><a href="index.php" class="btn">一覧に戻る</a></p>
</body>
</html>
