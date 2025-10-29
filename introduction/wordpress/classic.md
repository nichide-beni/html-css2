# クラシックテーマを作成してみよう

## ステップ1: 必要最低限の構成を試してみよう

必要なファイル構成。

前に作ったWordPressにそのまま以下のテーマを追加する。

```ini
wp-content/
└── themes/
    └── my-cls-theme/
        ├── style.css
        ├── index.php
        └── functions.php
```

style.cssには以下を記述してみよう(管理画面から認識されるようになる)

```css
/*
Theme Name: My Classic Theme
Author: 作成者(今回はなんでもOK)
Version: 1.0
*/
```


index.phpは以下の記述

```php
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?php bloginfo('name'); ?></title>
  <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
</head>
<body>
  <header><h1><?php bloginfo('name'); ?></h1></header>
  <main>
    <div class="template-label">テンプレート: index.php</div>
    <p><?php bloginfo('description'); ?></p>
  </main>
  <footer><p>&copy; <?php echo date('Y'); ?></p></footer>
</body>
</html>

```

functions.phpは以下の記述(空ファイルOK)

```php
<?php
// サムネイルサポートの有効化
add_theme_support('post-thumbnails');
```

- ここまできたら、管理画面からテーマを変更しよう
- VK系のプラグインは無効化しておこう
- 投稿ページがどのように表示されるか確認しよう
  - 結果の理由を考えてみよう

## ステップ2：CSSとJavaScriptの登録

functions.phpに追記する(※細かい使い方はリファレンスなどを参照すること)

- css/main.cssを作成する
- js/script.jsを作成する
- ページから読み込まれるかを開発者ツールで確認する

```php
/**
 * 有効化するスクリプトを追加する
 * HTMLからパスで読むことも可能だが、以下の方法を使うと重複読み込みやテーマ変更などにも強くなる
 */
function mytheme_enqueue_scripts() {
    wp_enqueue_style('mytheme-style', get_stylesheet_uri());
    wp_enqueue_style('mytheme-style', get_template_directory_uri() . '/css/main.css');
    // 最後のbool値でfooter出力が可能
    wp_enqueue_script('mytheme-script', get_template_directory_uri() . '/js/script.js', array(), null, true);
}
// アクションフック、特定の瞬間に処理を差し込むWordPressの仕様
add_action('wp_enqueue_scripts', 'mytheme_enqueue_scripts');

```

```css
/** style.css */
/*
Theme Name: My Classic Theme
Author: あなたの名前
Version: 1.0
*/

body {
  font-family: sans-serif;
  margin: 0;
  padding: 0;
  background: #f4f4f4;
  color: #333;
}

header, footer {
  background: #333;
  color: #fff;
  padding: 1em;
  text-align: center;
}

main {
  max-width: 800px;
  margin: 2em auto;
  padding: 1em;
  background: #fff;
  box-shadow: 0 0 5px rgba(0,0,0,0.1);
}

.template-label {
  background: #eee;
  padding: 0.5em;
  font-size: 0.9em;
  color: #666;
  border-left: 4px solid #999;
  margin-bottom: 1em;
}

nav {
  margin-top: 1em;
}

nav ul {
  list-style: none;
  padding: 0;
  display: flex;
  gap: 1em;
  justify-content: center;
}

nav a {
  text-decoration: none;
  color: #fff;
  background: #555;
  padding: 0.5em 1em;
  border-radius: 4px;
}

nav a:hover {
  background: #999;
}

.featured-image {
  margin-bottom: 1em;
  text-align: center;
}

.featured-image img {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
}

```

## ステップ3：固定ページと投稿ページ

### ファイル分割のために共通ファイルを用意しよう

header.php

```php
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php wp_title('|', true, 'right'); ?></title>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
  <header>
    <h1><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
    <p><?php bloginfo('description'); ?></p>
    <nav>
      <?php wp_nav_menu(array('theme_location' => 'main-menu')); ?>
    </nav>
  </header>
```

footer.php

```php
  <footer>
    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
  </footer>
  <?php wp_footer(); ?>
</body>
</html>
```

function.phpに追記

```php
function mytheme_setup() {
  register_nav_menus(array(
    'main-menu' => 'メインメニュー',
  ));
}
add_action('after_setup_theme', 'mytheme_setup');

```

### 投稿ページ用のテンプレートを用意しよう

single.php

```php
<?php get_header(); ?>
<main>
  <div class="template-label">テンプレート: single.php</div>
  <h1><?php the_title(); ?></h1>
  <?php if (has_post_thumbnail()) : ?>
    <?php the_post_thumbnail(); ?>
  <?php endif; ?>
  <?php the_content(); ?>
</main>
<?php get_footer(); ?>
```

### 固定ページ用のテンプレートを用意しよう

post.php

```php
<?php get_header(); ?>
<main>
  <div class="template-label">テンプレート: page.php</div>
  <h1><?php the_title(); ?></h1>
  <?php the_content(); ?>
</main>
<?php get_footer(); ?>

```

## ステップ4：投稿一覧ページ

archive.php

http://localhost:8881/info/ にアクセスしてみよう

```php
<?php get_header(); ?>
<main>
  <div class="template-label">テンプレート: archive.php</div>
  <h1>最新の投稿</h1>
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article>
      <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail(); ?>
      <?php endif; ?>
      <?php the_excerpt(); ?>
    </article>
  <?php endwhile; endif; ?>
  <div class="pagination"><?php the_posts_pagination(); ?></div>
</main>
<?php get_footer(); ?>
```

## ステップ5：カテゴリー別投稿ページ

category.php

http://localhost:8881/info/ をリロードしてみよう

```php
<?php get_header(); ?>
<main>
  <div class="template-label">テンプレート: category.php</div>
  <h1><?php single_cat_title(); ?></h1>
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article>
      <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail(); ?>
      <?php endif; ?>
      <?php the_excerpt(); ?>
    </article>
  <?php endwhile; endif; ?>
  <div class="pagination"><?php the_posts_pagination(); ?></div>
</main>
<?php get_footer(); ?>

```

## ステップ6：固定ページの別テンプレート

page-about.php

管理画面から固定ページを作成し、サイドメニューから作成したテンプレートを選べるか確認しよう

```php
<?php
/*
Template Name: About Page
*/
get_header(); ?>
<main>
  <div class="template-label">テンプレート: page-about.php</div>
  <h1><?php the_title(); ?></h1>

  <?php if (has_post_thumbnail()) : ?>
    <div class="featured-image">
      <?php the_post_thumbnail('large'); ?>
    </div>
  <?php endif; ?>

  <div class="about-content">
    <?php the_content(); ?>
  </div>
</main>
<?php get_footer(); ?>

```

## ステップ7：ウィジェットの登録

functions.phpに以下を追記

```php
function mytheme_widgets_init() {
  register_sidebar(array(
    'name' => 'サイドバー',
    'id' => 'sidebar-1',
    'before_widget' => '<div class="widget">',
    'after_widget' => '</div>',
    'before_title' => '<h2>',
    'after_title' => '</h2>',
  ));
}
add_action('widgets_init', 'mytheme_widgets_init');
```

index.phpを以下のように調整

```php
<?php get_header(); ?>
<main>
  <div class="template-label">テンプレート: index.php</div>
  <p><?php bloginfo('description'); ?></p>

  <aside>
    <div class="template-label">サイドバーを表示</div>
    <?php if (is_active_sidebar('sidebar-1')) : dynamic_sidebar('sidebar-1'); endif; ?>
  </aside>

</main>
<?php get_footer(); ?>
```

管理画面に「ウィジェット」が表示されることを確認し、TOPページで動作を確かめてみよう。

## ステップ8：カスタム投稿タイプ

投稿や固定ページのように、専用の投稿欄を設けたい場合の記述。

functions.phpに以下を追記する。

```php
function mytheme_custom_post_type() {
  register_post_type('works', array(
    'labels' => array(
      'name' => '制作実績',
      'singular_name' => '制作実績',
    ),
    'public' => true,
    'has_archive' => true,
    'supports' => array('title', 'editor', 'thumbnail'),
    'menu_position' => 5,
    'menu_icon' => 'dashicons-portfolio',
  ));
}
add_action('init', 'mytheme_custom_post_type');
```

制作実績の一覧ファイルとしてarchive-works.phpを作成する

```php
<?php get_header(); ?>
<main>
  <div class="template-label">テンプレート: archive-works.php</div>
  <h1>制作実績一覧</h1>
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article>
      <h2><?php the_title(); ?></h2>
      <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail(); ?>
      <?php endif; ?>
      <?php the_content(); ?>
    </article>
  <?php endwhile; endif; ?>
  <div class="pagination"><?php the_posts_pagination(); ?></div>
</main>
<?php get_footer(); ?>
```

- 管理画面にカスタム投稿が反映されているか確認しよう
- そのまま制作実績の投稿を作成してみよう
- /works/などの一覧画面を確認してみよう
  - うまく反映されない場合はパーマリンクを再保存してみよう
