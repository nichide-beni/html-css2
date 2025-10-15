<?php
require_once __DIR__ . '/db_config.php';
$pdo = connect_db();
if ($pdo === false) {
    echo 'データベース接続エラー';
    exit;
}

// データ取得, データベースの構造に合わせて取得
$sql = "SELECT id, name, content, created FROM chat_history ORDER BY id ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$chats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>チャット履歴一覧</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>チャット履歴一覧</h1>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>名前</th>
        <th>内容</th>
        <th>投稿日時</th>
        <th class="actions">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($chats as $chat): ?>
        <tr>
          <td><?php echo htmlspecialchars($chat['id']) ?></td>
          <td><?php echo htmlspecialchars($chat['name']) ?></td>
          <td><?php echo nl2br(htmlspecialchars($chat['content'])) ?></td>
          <td><?php echo htmlspecialchars($chat['created']) ?></td>
          <td class="actions">
            <button class="btn" onclick="location.href='sample2.php?id=<?php echo $chat['id'] ?>'">編集</button>
            <button class="btn" onclick="if(confirm('本当に削除しますか？')) location.href='delete.php?id=<?php echo $chat['id']; ?>'">削除</button>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <p><button class="btn" onclick="location.href='sample2.php'">新規追加</button></p>
</body>
</html>
