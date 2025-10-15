<?php
require_once 'db_config.php';
$pdo = connect_db();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === false || $id <= 0) {
  header('Location: index.php');
  exit;
}

// データ削除処理
$stmt = $pdo->prepare("DELETE FROM chat_history WHERE id = ?");
$stmt->execute([$id]);

header('Location: index.php');
exit;
