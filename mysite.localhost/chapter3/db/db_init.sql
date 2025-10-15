-- データベース作成
CREATE DATABASE IF NOT EXISTS sample_chat CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- データベース選択(phpMyAdmin上のクリックと同じ)
USE sample_chat;

-- テーブル作成
CREATE TABLE IF NOT EXISTS chat_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL, -- 名前は最大100文字想定
    content TEXT NOT NULL,
    created DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- サンプル初期データを実行(1回だけ)
INSERT INTO chat_history (name, content)
VALUES
  ('田中太郎', 'こんにちは、今日はいい天気ですね。'),
  ('佐藤花子', '週末の予定はみんなでピクニックです！'),
  ('鈴木一郎', 'データベースを学んでみます！'),
  ('高橋美咲', '昼寝をしたい気分です。'),
  ('ジョン', '宮城県は初めてきました。')
;


-- ※表示が変わらない場合phpMyAdminのリロードを実行
