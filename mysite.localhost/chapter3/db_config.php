<?php

function connect_db() {
  $db_host = 'localhost';
  $db_user = 'root';
  $db_password = 'root';
  $db_db = 'sample_chat';
  $dsn = "mysql:host=$db_host;dbname=$db_db;charset=utf8mb4";

  try {
    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
  } catch (PDOException $e) {
    // ログ出力やエラーメッセージの表示など必要に応じて追加
    return false;
  }
}
