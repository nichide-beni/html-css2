# クラシックエディターを実践的にカスタマイズする

Studioから新しいサイトを立ち上げよう「my-second-test」など。

## ベーステーマをインストールしよう

外観のテーマから「Twenty Twelve」をインストールしよう(だめだったら公式のzipをインストール)。

ベーステーマは2012年と古いが、メンテナンスはされており、余計なコードが少なくベースとしては活用しやすい。

functionsにはいろいろな処理が書いているので、さらっと読んでみよう。

## テーマのカスタマイズ内容

| 画面要素       | 表示内容                                                                 | 対応コード・関数例                                       | 対応ファイル           |
|----------------|--------------------------------------------------------------------------|----------------------------------------------------------|------------------------|
| ヘッダー        | サイトタイトル「Twenty Twelve」、サブタイトル、水平ナビゲーション        | `wp_nav_menu()` によるメニュー表示                      | `header.php`           |
| ナビゲーションバー | 黒背景に白文字のメニュー項目＋検索アイコン                                 | `register_nav_menus()` → `wp_nav_menu()` + CSS調整       | `functions.php`, `header.php`, `style.css` |
| 投稿一覧（2件） | サムネイル画像、タイトル、投稿日、カテゴリ、抜粋、"Read more" リンク       | `the_post_thumbnail()`, `the_title()`, `get_the_date()`, `the_category()`, `the_excerpt()` | `index.php`, `content.php` |
| サイドバー      | 「RECENT POSTS」見出しとリンク                                           | `dynamic_sidebar()` によるウィジェット表示              | `sidebar.php`, `functions.php` |
| 背景とレイアウト | メイン白背景、サイドバー灰色、全体に余白と区切り線                         | `.post-grid`, `.post-card`, `.sidebar` などのCSSクラス   | `style.css`            |

## 子テーマを用意しよう

テーマを直接書き換えた場合に、アップデートが入ると上書きされて消えてしまうという問題が発生する。

その問題を回避するため、子テーマを用意して親テーマを継承し、必要な部分をカスタマイズするという形式を取る。

### 子テーマの作成

WordPressの /wp-content/themes/ に twentytwelve-child というフォルダを作成

### twentytwelve-child/style.cssの作成

```css
/*
Theme Name: Twenty Twelve Child
Template: twentytwelve
Version: 1.0
*/

body {
  background-color: #f9f9f9;
}
```

### twentytwelve-child/functions.php の作成（CSSの継承）

親テーマのCSSを読み込んだ上で、子テーマのCSSを追加

```php
<?php
function twentytwelve_child_enqueue_styles() {
  wp_enqueue_style( 'twentytwelve-style', get_template_directory_uri() . '/style.css' );
  wp_enqueue_style( 'twentytwelve-child-style', get_stylesheet_uri(), array('twentytwelve-style') );
}
add_action( 'wp_enqueue_scripts', 'twentytwelve_child_enqueue_styles' );
```

### 子テーマの有効化

管理画面から子テーマの「twentytwelve-child」を有効化

パーマリンクをカスタムの「カテゴリー名/投稿名」に変更

## Step 1：投稿一覧ページのテンプレート作成（index.php）

目的：投稿が並ぶページの構造を理解し、タイトル・本文・画像を表示する

ファイル：twentytwelve-child/index.php

- WordPressが投稿を自動で並べてくれる仕組みを体験
- 投稿のタイトル・本文・画像を表示するHTML構造を作成
- 「投稿があるかどうかを確認して、1つずつ表示する」流れを理解

関数の補足

- get_header() や get_footer() は「header.php, footer.phpなどの共通部分を読み込む命令」
- the_title() や the_content() は「title, 本文などの投稿の中身を表示する命令」

```php
<?php get_header(); ?>

<div class="main-area">
  <div class="post-grid">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      <article class="post-card">
        <a href="<?php the_permalink(); ?>">
          <?php if ( has_post_thumbnail() ) : ?>
            <div class="thumb"><?php the_post_thumbnail('medium'); ?></div>
          <?php endif; ?>
          <h2><?php the_title(); ?></h2>
        </a>
        <div class="meta">
          <span class="date"><?php echo get_the_date(); ?></span>
          <span class="cat"><?php the_category(', '); ?></span>
        </div>
        <div class="excerpt"><?php the_excerpt(); ?></div>
      </article>
    <?php endwhile; endif; ?>
  </div>

  <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
```

## Step 2：投稿詳細ページ（single.php）

目的

- 投稿1件の詳細ページの構造を理解し、デザインに合わせてカスタマイズする
- Before（親テーマ）とAfter（子テーマ）の違いをコードと画面で把握する

```php
<?php get_header(); ?>

<article class="single-post">
  <h1><?php the_title(); ?></h1>
  <div class="meta">
    <span class="date"><?php echo get_the_date(); ?></span> /
    <span class="author"><?php the_author(); ?></span>
  </div>
  <?php if ( has_post_thumbnail() ) : ?>
    <div class="thumb"><?php the_post_thumbnail('large'); ?></div>
  <?php endif; ?>
  <div class="content"><?php the_content(); ?></div>
</article>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
```

## Step 3：固定ページ（page.php）

```php
<?php get_header(); ?>

<main class="page-content">
  <h1><?php the_title(); ?></h1>
  <div class="content"><?php the_content(); ?></div>
</main>

<?php get_footer(); ?>

```

### Step４ お問い合わせページの作成手順(固定ページ)

お問い合わせ用のテンプレートを作成する(page-contact.php)。

```php
<?php
/*
Template Name: Contact Page
*/
get_header(); ?>

<main class="contact-page">
  <h1><?php the_title(); ?></h1>

  <div class="content">
    <?php
    // 固定ページの本文を表示（ショートコードも含まれる）
    the_content();
    ?>
  </div>
</main>

<?php get_footer(); ?>
```

#### お問い合わせプラグインを入れよう

- 管理画面から、「Contact Form 7」を導入する
- インストールから有効化まで実行する

サイドメニューからデフォルトのフォームを確認し、以下のような項目を生成する(GUI)

メール2を使用し、相手先への自動返信メールを作成する。

警告が出るが、プラグイン元のページでスパム対策についての注意を喚起される。

```ini
題名: お問い合わせありがとうございます | [_site_title] "[your-subject]"
```

#### 固定ページにお問い合わせフォームを設置しよう

- 固定ページから「お問い合わせ」というページを作成しよう
- テンプレートをお問い合わせように設定しよう
- お問い合わせのショートコードをブロックではろう

```html
[contact-form-7 id="{{ここにID}}" title="{{ここにタイトル}}"]
```

ローカル環境で実行はできないが、公開してページの動作までは確認しよう。

スタイルも調整しておこう(style.css。

```css
.contact-page {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
  background: #fff;
}
.contact-page input,
.contact-page textarea {
  width: 100%;
  padding: 0.5rem;
  margin-bottom: 1rem;
  border: 1px solid #ccc;
}
.contact-page input[type="submit"] {
  background: #333;
  color: #fff;
  padding: 0.75rem 1.5rem;
  border: none;
  cursor: pointer;
}

```

## Step 5：トップページ（front-page.php）

- サイトの顔となるトップページを自由にデザインする
- 投稿一覧とは異なる構成で、ヒーローセクション(KV)や最新投稿などを表示する

```php
<?php get_header(); ?>

<section class="hero">
  <h1>ようこそ</h1>
  <p>このサイトはTwenty Twelveをベースにしたカスタムテーマです。</p>
</section>

<section class="latest-posts">
  <h2>最新の記事</h2>
  <div class="post-grid">
    <?php
    $latest = new WP_Query(['posts_per_page' => 3]);
    while ( $latest->have_posts() ) : $latest->the_post(); ?>
      <article class="post-card">
        <a href="<?php the_permalink(); ?>">
          <?php if ( has_post_thumbnail() ) : ?>
            <div class="thumb"><?php the_post_thumbnail('medium'); ?></div>
          <?php endif; ?>
          <h3><?php the_title(); ?></h3>
          <div class="meta">
            <span><?php echo get_the_date(); ?></span>
            <span><?php the_category(', '); ?></span>
          </div>
          <div class="excerpt"><?php the_excerpt(); ?></div>
        </a>
      </article>
    <?php endwhile; wp_reset_postdata(); ?>
  </div>
</section>

<?php get_footer(); ?>

```

ページも増えてきたので、TOPやその他固定ページなどのスタイルを調整する。

- カードデザイン

```css
/* グリッド全体の余白と並び */
.post-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 2rem;
  margin: 2rem 0;
}

/* カードの基本スタイル */
.post-card {
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  overflow: hidden;
  transition: box-shadow 0.3s ease;
  display: flex;
  flex-direction: column;
}

/* ホバー時の影強調 */
.post-card:hover {
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* サムネイル画像 */
.post-card .thumb img {
  width: 100%;
  height: auto;
  display: block;
}

/* タイトル */
.post-card h2,
.post-card h3 {
  font-size: 1.2rem;
  margin: 1rem;
  color: #333;
}

/* メタ情報（投稿日・カテゴリ） */
.post-card .meta {
  font-size: 0.85rem;
  color: #666;
  margin: 0 1rem 0.5rem;
}

/* 抜粋 */
.post-card .excerpt {
  font-size: 0.95rem;
  line-height: 1.6;
  margin: 0 1rem 1rem;
  color: #444;
}

/* リンク全体を囲む場合の調整 */
.post-card a {
  text-decoration: none;
  color: inherit;
}
```

- 共通css

```css
/* 全体のベース余白 */
body {
  background-color: #f9f9f9;
  font-family: "Helvetica Neue", sans-serif;
  line-height: 1.7;
  color: #333;
  margin: 0;
  padding: 0;
}

/* ページタイトル（h1） */
h1 {
  font-size: 2rem;
  margin: 2rem 0 1rem;
  line-height: 1.3;
  font-weight: bold;
  text-align: center;
}

/* セクションタイトル（h2, h3） */
h2, h3 {
  font-size: 1.5rem;
  margin: 1.5rem 0 1rem;
  font-weight: 600;
}

/* セクション間の余白 */
section {
  padding: 2rem 1rem;
  margin-bottom: 2rem;
}

/* ヒーローセクション */
.hero {
  background: #e9f1f7;
  padding: 3rem 1rem;
  text-align: center;
}
.hero h1 {
  font-size: 2.5rem;
  margin-bottom: 1rem;
}
.hero p {
  font-size: 1.1rem;
  color: #555;
}

/* お問い合わせページの調整 */
.contact-page {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
  background: #fff;
}
.contact-page h1 {
  font-size: 2rem;
  margin-bottom: 1.5rem;
}

```

## Step 6：アーカイブページ（archive.php）

- カテゴリ一覧やタグ一覧などのアーカイブページをカード型で表示
- 投稿一覧と同様のデザインを適用しつつ、タイトルや説明を追加

```php
<?php get_header(); ?>

<main class="archive-page">
  <h1 class="archive-title"><?php the_archive_title(); ?></h1>
  <p class="archive-description"><?php the_archive_description(); ?></p>

  <div class="post-grid">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      <article class="post-card">
        <a href="<?php the_permalink(); ?>">
          <?php if ( has_post_thumbnail() ) : ?>
            <div class="thumb"><?php the_post_thumbnail('medium'); ?></div>
          <?php endif; ?>
          <h3><?php the_title(); ?></h3>
          <div class="meta">
            <span><?php echo get_the_date(); ?></span>
            <span><?php the_category(', '); ?></span>
          </div>
          <div class="excerpt"><?php the_excerpt(); ?></div>
        </a>
      </article>
    <?php endwhile; endif; ?>
  </div>
</main>

<?php get_footer(); ?>

```

アーカイブページ用のスタイルを設定する。

```css
/* アーカイブページ全体 */
.archive-page {
  max-width: 1000px;
  margin: 0 auto;
  padding: 2rem 1rem;
}

/* アーカイブタイトル */
.archive-title {
  font-size: 2rem;
  margin-bottom: 0.5rem;
  text-align: center;
}

/* アーカイブ説明 */
.archive-description {
  font-size: 1rem;
  color: #666;
  margin-bottom: 2rem;
  text-align: center;
}

```

category.phpが強いので、「http://localhost:8882/アーカイブ年/」など、日付アーカイブの方で動作を確認しよう。

## Step7 子テーマの 404.php

```php
<?php get_header(); ?>

<main class="not-found-page">
  <h1 class="not-found-title">ページが見つかりません</h1>
  <p class="not-found-message">
    お探しのページは存在しないか、移動された可能性があります。
  </p>
  <a href="<?php echo home_url(); ?>" class="back-home">トップページへ戻る</a>
</main>

<?php get_footer(); ?>

```

404ページ用にstyle.cssを更新する。

```css
.not-found-page {
  max-width: 800px;
  margin: 0 auto;
  padding: 4rem 1rem;
  text-align: center;
}

.not-found-title {
  font-size: 2.5rem;
  margin-bottom: 1rem;
}

.not-found-message {
  font-size: 1.1rem;
  color: #666;
  margin-bottom: 2rem;
}

.back-home {
  display: inline-block;
  padding: 0.75rem 1.5rem;
  background: #0073aa;
  color: #fff;
  text-decoration: none;
  border-radius: 4px;
  transition: background 0.3s ease;
}
.back-home:hover {
  background: #005a8c;
}

```

## STEP8 ページ全体を整えよう

```css
/*
Theme Name: Twenty Twelve Child
Template: twentytwelve
Version: 1.0
*/

/* ====== ベーススタイル ====== */
body {
  background-color: #f9f9f9;
  font-family: "Helvetica Neue", sans-serif;
  line-height: 1.7;
  color: #333;
  margin: 0;
  padding: 0;
}

main {
  max-width: 1000px;
  margin: 0 auto;
  padding: 2rem 1rem;
}

/* ====== タイトル・見出し ====== */
h1 {
  font-size: 2.2rem;
  margin: 2rem 0 1rem;
  font-weight: bold;
  line-height: 1.3;
  text-align: center;
}

h2 {
  font-size: 1.6rem;
  margin: 1.5rem 0 1rem;
  font-weight: 600;
}

h3 {
  font-size: 1.3rem;
  margin: 1rem 0 0.5rem;
  font-weight: 500;
}

/* ====== セクション余白 ====== */
section {
  padding: 2rem 1rem;
  margin-bottom: 2rem;
}

/* ====== リンク・ボタン ====== */
a {
  color: #0073aa;
  text-decoration: none;
}
a:hover {
  text-decoration: underline;
}

button,
input[type="submit"] {
  background: #0073aa;
  color: #fff;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 4px;
  cursor: pointer;
}
button:hover,
input[type="submit"]:hover {
  background: #005a8c;
}

/* ====== 投稿カードレイアウト ====== */
.post-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 2rem;
  margin: 2rem 0;
}

.post-card {
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  overflow: hidden;
  transition: box-shadow 0.3s ease;
  display: flex;
  flex-direction: column;
}

.post-card:hover {
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.post-card .thumb img {
  width: 100%;
  height: auto;
  display: block;
}

.post-card h2,
.post-card h3 {
  font-size: 1.2rem;
  margin: 1rem;
  color: #333;
}

.post-card .meta {
  font-size: 0.85rem;
  color: #666;
  margin: 0 1rem 0.5rem;
}

.post-card .excerpt {
  font-size: 0.95rem;
  line-height: 1.6;
  margin: 0 1rem 1rem;
  color: #444;
}

.post-card a {
  text-decoration: none;
  color: inherit;
}

/* ====== ヒーローセクション（front-page） ====== */
.hero {
  background: #e9f1f7;
  padding: 4rem 1rem;
  text-align: center;
}
.hero h1 {
  font-size: 2.5rem;
  margin-bottom: 1rem;
}
.hero p {
  font-size: 1.2rem;
  color: #555;
}

/* ====== アーカイブページ ====== */
.archive-page {
  max-width: 1000px;
  margin: 0 auto;
  padding: 2rem 1rem;
}
.archive-title {
  font-size: 2rem;
  margin-bottom: 0.5rem;
  text-align: center;
}
.archive-description {
  font-size: 1rem;
  color: #666;
  margin-bottom: 2rem;
  text-align: center;
}

/* ====== お問い合わせページ ====== */
.contact-page {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
  background: #fff;
}
.contact-page h1 {
  font-size: 2rem;
  margin-bottom: 1.5rem;
}
.contact-page input,
.contact-page textarea {
  width: 100%;
  padding: 0.5rem;
  margin-bottom: 1rem;
  border: 1px solid #ccc;
}
.contact-page input[type="submit"] {
  background: #333;
  color: #fff;
  padding: 0.75rem 1.5rem;
  border: none;
  cursor: pointer;
}

/* ====== 404ページ ====== */
.not-found-page {
  max-width: 800px;
  margin: 0 auto;
  padding: 4rem 1rem;
  text-align: center;
}
.not-found-title {
  font-size: 2.5rem;
  margin-bottom: 1rem;
}
.not-found-message {
  font-size: 1.1rem;
  color: #666;
  margin-bottom: 2rem;
}
.back-home {
  display: inline-block;
  padding: 0.75rem 1.5rem;
  background: #0073aa;
  color: #fff;
  text-decoration: none;
  border-radius: 4px;
  transition: background 0.3s ease;
}
.back-home:hover {
  background: #005a8c;
}

/* ====== サイドバー ====== */
.sidebar {
  background: #f5f5f5;
  padding: 1.5rem;
  margin-top: 2rem;
  border-radius: 6px;
  font-size: 0.95rem;
}
.sidebar h2,
.sidebar h3 {
  font-size: 1.1rem;
  margin-top: 1.5rem;
}
.sidebar ul {
  list-style: none;
  padding-left: 0;
}
.sidebar li {
  margin-bottom: 0.5rem;
}

```

