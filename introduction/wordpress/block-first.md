# 初期ブロックテーマを構築しよう

ブロックテーマとして、空のテーマファイルを作成する。  
「/{pc-name}/Local Sites/{Site Name}/app/public/wp-content/themes/」に「my-block-first」などを作成する。  

## 最小限の構成で作成しよう

まずはテーマ名など基本設定のcssを作成しよう。  
公式テーマを参考に、コメント項目を埋めておこう。  

> style.css

```css
/*
Theme Name: my-block-first
Theme URI: https://example.com
Author: サンプルさん
Author URI: https://example.com
Description: サンプルのwordpressです
Requires at least: 6.7
Tested up to: 6.8
Requires PHP: 8.2
Version: 1.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: my-block-theme.local
Tags: 後で変えよう,one-column, custom-colors, custom-menu, custom-logo, editor-style, featured-images, full-site-editing, block-patterns, rtl-language-support, sticky-post, threaded-comments, translation-ready, wide-blocks, block-styles, style-variations, accessibility-ready, blog, portfolio, news
*/
```

汎用ページおよびハンドリングページであるindexを用意する。  

> templates/index.html

```html
<!-- wp:template-part {"slug":"header","area":"header"} /-->

<!-- wp:group {"layout":{"type":"constrained"}} -->
  <!-- wp:post-content /-->
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","area":"footer"} /-->
```

ここまできたら、管理画面から作成中のテーマを有効化し、表示を確認する。

## theme.jsonを設定してみよう

テーマの基本設定として、wordpressのデフォルト設定をjsonで上書き、追記することができる。  
ここでは、カラーパレットを変更してみよう。  

> theme.json

```json
{
  "$schema": "https://schemas.wp.org/trunk/theme.json",
  "version": 3,
  "settings": {
    "color": {
      "palette": [
        {
          "name": "Primary Blue",
          "slug": "primary-blue",
          "color": "#0077b6"
        },
        {
          "name": "Accent Light Blue",
          "slug": "accent-blue",
          "color": "#90e0ef"
        },
        {
          "name": "Neutral Gray",
          "slug": "neutral-gray",
          "color": "#f5f5f5"
        }
      ]
    }
  }
}

```

functions.phpを設定し、カラー設定を反映しよう。

> functions.php

```php
<?php
function fishing_theme_setup() {
  add_theme_support('editor-styles');
  add_editor_style('style.css');
}
add_action('after_setup_theme', 'fishing_theme_setup');

function fishing_theme_enqueue_styles() {
  wp_enqueue_style('fishing-theme-style', get_stylesheet_uri()); // フロント用
}
add_action('wp_enqueue_scripts', 'fishing_theme_enqueue_styles');

```

投稿を作成し、文字色を変えてプレビューしてみよう。

## 欠けているheaderとfooterを作成しよう

> parts/header.html

```html
<!-- wp:group {"tagName":"header","layout":{"type":"constrained"}} -->
  <!-- wp:site-title /-->
  <!-- wp:navigation {"layout":{"type":"flex","justifyContent":"right"}} /-->
<!-- /wp:group -->
```

> parts/footer.html

```html
<!-- wp:group {"tagName":"footer","layout":{"type":"constrained"}} -->
  <!-- wp:paragraph --><p>© 2025 釣具専門サイト All rights reserved.</p><!-- /wp:paragraph -->
<!-- /wp:group -->
```

管理画面のエディターから反映させよう。  
うまく反映されない場合は前の設定が残っているので、テンプレートをリセットしよう。  

## スタイルを設定しておこう

少しだけデザインを整えておこう、以下をコピペする。  
※後で調整するのでざっくりでいい。  

> style.css

```css
:root {
  --primary-blue: #0077b6;
  --accent-blue: #90e0ef;
  --light-gray: #f1f5f9;
  --white: #ffffff;
  --dark-gray: #333333;
  --font-main: 'Noto Sans JP', sans-serif;
  --max-width: 1200px;
}

body {
  font-family: var(--font-main);
  background-color: var(--light-gray);
  color: var(--dark-gray);
  margin: 0;
  padding: 0;
  line-height: 1.8;
  font-size: 16px;
}

.container {
  max-width: var(--max-width);
  margin: 0 auto;
  padding: 2rem 1rem;
}

header,
footer {
  background-color: var(--primary-blue);
  color: var(--white);
  padding: 2rem 1rem;
}

header h1,
footer h1,
footer p {
  margin: 0;
  font-weight: 600;
}

a {
  color: var(--primary-blue);
  text-decoration: none;
  transition: color 0.3s ease;
}

a:hover {
  color: var(--accent-blue);
  text-decoration: underline;
}

img {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.wp-block-button__link {
  background-color: var(--primary-blue);
  color: var(--white);
  padding: 0.8em 1.6em;
  border-radius: 6px;
  font-weight: bold;
  transition: background-color 0.3s ease;
  display: inline-block;
}

.wp-block-button__link:hover {
  background-color: var(--accent-blue);
}

.wp-block-cover {
  color: var(--white);
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
  background-size: cover;
  background-position: center;
  min-height: 300px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.wp-block-group {
  margin-block: 3rem;
  padding-inline: 1rem;
}

.wp-block-heading {
  font-weight: 700;
  margin-bottom: 1rem;
  border-left: 4px solid var(--primary-blue);
  padding-left: 0.5rem;
  font-size: 1.5rem;
}

.wp-block-columns {
  display: flex;
  gap: 2rem;
  flex-wrap: wrap;
}

.wp-block-column {
  background-color: var(--white);
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  flex: 1 1 45%;
}

.wp-block-post-featured-image {
  margin-bottom: 1rem;
  border-radius: 6px;
  overflow: hidden;
}

.wp-block-post-title {
  font-size: 1.2rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

.wp-block-post-excerpt {
  font-size: 0.95rem;
  color: #555;
  margin-bottom: 1rem;
}

.wp-block-query {
  margin-top: 2rem;
}

.wp-block-comment {
  margin-top: 2rem;
  border-top: 1px solid #ccc;
  padding-top: 1rem;
}

.wp-block-shortcode {
  margin-top: 2rem;
}

.wp-block-button {
  margin-top: 1.5rem;
}

@media screen and (max-width: 768px) {
  .wp-block-columns {
    flex-direction: column;
  }

  .wp-block-column {
    margin-bottom: 2rem;
  }

  .wp-block-button__link {
    width: 100%;
    text-align: center;
  }

  .wp-block-heading {
    font-size: 1.3rem;
  }
}
```

## 構築済みページのソースを細かく調整する

ブロックテーマやカスタムブロック(エディターのブロックのこと)は、スタイルやクラスに至るまで細部の検証を行う。  
JSON化した状態での正確さを確認するため、独自で作る場合は順番にも配慮しながら構築する。  

> parts/header.html

```html
<!-- wp:group {"tagName":"header","layout":{"type":"constrained"},"style":{"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
<header class="wp-block-group" style="margin-top:0;margin-bottom:0;">
  <!-- wp:site-title /-->
  <!-- wp:navigation {"layout":{"type":"flex","justifyContent":"right"}} /-->
</header>
<!-- /wp:group -->
```

> parts/footer.html

```html
<!-- wp:group {"tagName":"footer","layout":{"type":"constrained"},"style":{"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
<footer class="wp-block-group" style="margin-top:0;margin-bottom:0;">
  <!-- wp:paragraph --><p>© 2025 釣具専門サイト All rights reserved.</p><!-- /wp:paragraph -->
</footer>
<!-- /wp:group -->
```

> templates/index.html

```html
<!-- wp:template-part {"slug":"header","area":"header"} /-->
<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
  <!-- wp:post-content /-->
</div>
<!-- /wp:group -->
<!-- wp:template-part {"slug":"footer","area":"footer"} /-->
```

## TOPページを作成する

これまでの情報を参考に、TOPページを構成してみよう。  
wpコードに一致するHTMLを設置しておくことで、管理画面エラーなくデフォルト設定が可能になる。  
※not foundあたりは無視してOK。  

> templates/front-page.html

```html
<!-- wp:template-part {"slug":"header","area":"header"} /-->

<!-- wp:cover {"url":"path/to/kv.jpg","dimRatio":30,"overlayColor":"primary-blue","minHeight":400} -->
<div class="wp-block-cover" style="min-height:400px">
  <img class="wp-block-cover__image-background" alt="" src="path/to/kv.jpg" data-object-fit="cover"/>
  <span aria-hidden="true" class="wp-block-cover__background has-primary-blue-background-color has-background-dim-30 has-background-dim"></span>
  <div class="wp-block-cover__inner-container">
    <!-- wp:heading {"level":1} -->
    <h1 class="wp-block-heading">ようこそ、釣具専門サイトへ</h1>
    <!-- /wp:heading -->

    <!-- wp:paragraph {"align":"center"} -->
    <p class="has-text-align-center">釣り人のための情報と道具がここに集結！</p>
    <!-- /wp:paragraph -->
  </div>
</div>
<!-- /wp:cover -->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
  <!-- wp:heading --><h2 class="wp-block-heading">サービス紹介</h2><!-- /wp:heading -->
  <!-- wp:paragraph --><p>当店で取り扱っている釣具の詳細は以下のページでご覧いただけます。</p><!-- /wp:paragraph -->
  <!-- wp:buttons -->
    <div class="wp-block-buttons">
      <!-- wp:button -->
      <div class="wp-block-button">
        <a class="wp-block-button__link wp-element-button">釣具一覧を見る</a>
      </div>
      <!-- /wp:button -->
    </div>
  <!-- /wp:buttons -->
</div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
  <!-- wp:heading --><h2 class="wp-block-heading">最新のお知らせ</h2><!-- /wp:heading -->

  <!-- wp:query {"query":{"perPage":3,"postType":"post"}} -->
    <!-- wp:post-template -->
      <!-- wp:post-featured-image {"isLink":true} /-->
      <!-- wp:post-title {"isLink":true} /-->
      <!-- wp:post-date /-->
      <!-- wp:post-excerpt /-->
    <!-- /wp:post-template -->
  <!-- /wp:query -->
  <!-- wp:button {"text":"お知らせ一覧へ"} -->
  <div class="wp-block-button">
    <a class="wp-block-button__link wp-element-button">お知らせ一覧へ</a>
  </div>
  <!-- /wp:button -->
</div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group">
  <!-- wp:heading {"level":2} --><h2>魚拓ギャラリー</h2><!-- /wp:heading -->
  <!-- wp:query {"query":{"perPage":3,"postType":"gyotaku"}} -->
    <!-- wp:post-template -->
      <!-- wp:post-featured-image {"isLink":true} /-->
      <!-- wp:post-title {"isLink":true} /-->
      <!-- wp:acf/field {"name":"fish_type"} /-->
    <!-- /wp:post-template -->
  <!-- /wp:query -->
  <!-- wp:button {"text":"魚拓一覧へ"} -->
  <div class="wp-block-button">
    <a class="wp-block-button__link wp-element-button">魚拓一覧へ</a>
  </div>
  <!-- /wp:button -->
</div>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer", "area":"footer"} /-->

```

## アーカイブページを作成してみよう

一覧をループで表示させるようなアーカイブページを作成する。  
クエリループでスタイルなどを適用する場合は設定が必要。

> templates/archive.html

```html
<!-- wp:template-part {"slug":"header","area":"header"} /-->
<!-- wp:heading {"level":1} --><h1>お知らせ一覧</h1><!-- /wp:heading -->
<!-- wp:query {"query":{"postType":"post"}} -->
  <!-- wp:post-template -->
    <!-- wp:post-title {"level":1} /-->
    <!-- wp:post-date /-->
    <!-- wp:post-excerpt /-->
  <!-- /wp:post-template -->

  <!-- wp:query-pagination -->
    <!-- wp:query-pagination-previous /-->
    <!-- wp:query-pagination-numbers /-->
    <!-- wp:query-pagination-next /-->
  <!-- /wp:query-pagination -->
<!-- /wp:query -->
<!-- wp:template-part {"slug":"footer","area":"footer"} /-->
```
