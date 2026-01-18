# Puock WordPress Theme - 開發指南

## 專案概述

Puock 是一款基於 WordPress 開發的高顏值自我調整主題，支援白天與黑夜模式、無重新整理載入、多種佈局模式等特性。

**專案倉庫**: https://github.com/Licoy/wordpress-theme-puock

## 技術棧

### 後端
- **PHP**: 7.4+
- **WordPress**: 6.0+
- **Composer**: 依賴管理

### 前端
- **JavaScript**: ES6+
- **CSS前置處理器**: Less
- **後臺框架**: Vue3 + NaiveUI
- **圖示庫**: Font Awesome, Strawberry Icon

### 構建工具
- **Gulp**: 任務執行器
- **Babel**: JavaScript轉譯器
- **包管理器**: npm/pnpm

## 專案結構

```
puock/
├── inc/                   # 核心功能目錄
│   ├── ajax/             # Ajax 處理
│   │   ├── ai.php        # AI 相關介面
│   │   ├── page-poster.php  # 海報產生
│   │   └── ...
│   ├── classes/          # 類檔案
│   ├── ext/              # 擴充功能
│   │   └── moments.php   # 朋友圈功能
│   ├── fun/              # 功能函數
│   │   ├── ajax.php      # Ajax 公共函數
│   │   ├── cache.php     # 快取函數
│   │   ├── comment.php   # 評論功能
│   │   ├── short-code.php # 短代碼
│   │   └── ...
│   ├── oauth/            # OAuth 登入
│   │   └── callback/
│   ├── page/             # 頁面功能
│   │   └── user-center.php
│   ├── setting/          # 後臺設定
│   │   ├── options/      # 選項組態
│   │   └── template.php  # 範本檔
│   ├── init.php          # 初始化
│   ├── seo.php           # SEO 功能
│   └── metas.php         # 中繼資料
├── assets/               # 前端資源
│   ├── dist/             # 編譯後的檔（不修改）
│   ├── fonts/            # 字體檔
│   ├── img/              # 圖片資源
│   ├── js/               # JavaScript 源碼
│   │   ├── admin.js      # 後臺 JS
│   │   ├── puock.js      # 前臺核心 JS
│   │   └── page-ai.js    # AI 頁面 JS
│   ├── libs/             # 第三方庫
│   └── style/            # Less 源碼
├── templates/            # 主題範本
│   ├── module-*.php      # 模組元件
│   ├── box-*.php         # 盒子組件
│   └── content-none.php
├── pages/                # 自訂頁面範本
│   ├── template-*.php    # 各類頁面範本
├── gutenberg/            # Gutenberg 區塊
│   └── components/
├── languages/            # 語言包
├── vendor/               # Composer 依賴（不修改）
├── update-checker/       # 更新檢查器（不修改）
├── ad/                   # 廣告位元範本
├── libs/                 # 前端庫源碼
├── cache/                # 快取目錄
├── .github/              # GitHub 組態
│   └── workflows/        # GitHub Actions
├── functions.php         # 主題主檔案
├── style.css             # 主題樣式聲明
├── gulpfile.js           # Gulp 構建組態
├── .babelrc              # Babel 組態
├── package.json          # npm 包組態
├── composer.json         # Composer 組態
└── README.md             # 專案說明
```

## 開發環境搭建

### 環境要求
- Node.js 14+
- PHP 7.4+
- WordPress 6.0+
- Composer（可選）

### 安裝步驟

1. **拷貝專案**
```bash
git clone https://github.com/Licoy/wordpress-theme-puock.git
cd wordpress-theme-puock/wp-content/themes/puock
```

2. **安裝前端依賴**
```bash
npm install
# 或使用 pnpm
pnpm install
```

3. **安裝 PHP 依賴** (可選)
```bash
composer install
```

## 開發命令

### 構建命令
```bash
# 構建生產版本
npm run build

# 開發模式（監聽檔變化）
npm run dev
```

構建過程會執行以下任務:
- 編譯 Less 檔到 `assets/dist/style/`
- 轉譯並壓縮 JS 檔到 `assets/dist/js/`
- 合併並壓縮第三方庫到 `assets/dist/`

### 修改原始檔案時的注意事項
- **修改 Less**: 編輯 `assets/style/*.less`，構建後產生 `.min.css`
- **修改 JS**: 編輯 `assets/js/*.js`，構建後產生 `.min.js`
- **庫檔案**: 修改 `assets/libs/basic/` 中的檔
- **不要直接修改**: `assets/dist/` 中的檔案會被構建覆蓋

## 核心功能模組

### 1. 主題設定系統
- **位置**: `inc/setting/`
- **設定檔**: `inc/setting/options/*.php`
- **範本**: `inc/setting/template.php`
- **後臺框架**: Vue3 + NaiveUI

### 2. Ajax 功能
- **入口**: `inc/ajax/index.php`
- **功能**:
  - AI 對話: `inc/ajax/ai.php`
  - 海報產生: `inc/ajax/page-poster.php`
  - OAuth 登入: `inc/ajax/page-oauth-login.php`
  - 前臺登入: `inc/ajax/page-front-login.php`

### 3. 評論系統
- **功能函數**: `inc/fun/comment.php`
- **Ajax 處理**: `inc/fun/comment-ajax.php`
- **E-mail 通知**: `inc/fun/comment-notify.php`

### 4. OAuth 登入
- **主檔案**: `inc/oauth/oauth.php`
- **回檔**: `inc/oauth/callback/*.php`
- **支援的平臺**: QQ, Github, Gitee, 微博

### 5. 短代碼系統
- **位置**: `inc/fun/short-code.php`
- **支援功能**:
  - 下載按鈕
  - 評論後可見
  - 登入後可見
  - 提示框
  - Github 卡片
  - 隱藏內容

### 6. SEO 優化
- **主檔案**: `inc/seo.php`
- **分類 SEO**: `inc/category-seo.php`
- **功能**: 標題優化、描述優化、關鍵字優化

### 7. 用戶中心
- **類檔案**: `inc/classes/PuockUserCenter.php`
- **頁面**: `inc/page/user-center.php`

### 8. 擴充功能
- **朋友圈**: `inc/ext/moments.php`
- **AI 功能**: `inc/ajax/ai.php`

## 主題範本

### 主要範本檔
- `header.php` - 頭部範本
- `footer.php` - 底部範本
- `index.php` - 首頁
- `single.php` - 文章頁
- `page.php` - 頁面
- `category.php` - 分類頁
- `tag.php` - 標籤頁
- `search.php` - 搜尋網頁
- `author.php` - 作者頁
- `404.php` - 404 頁面
- `sidebar.php` - 側邊欄

### 自訂頁面範本
- `pages/template-chatgpt.php` - ChatGPT 頁面
- `pages/template-moments.php` - 朋友圈頁面
- `pages/template-links.php` - 友情連結
- `pages/template-archives.php` - 文章歸檔
- `pages/template-reads.php` - 讀者牆
- `pages/template-tags.php` - 標籤頁
- `pages/template-book.php` - 書籍推薦
- `pages/template-random.php` - 隨機文章

### 模組元件
- `templates/module-post.php` - 文章模組
- `templates/module-posts.php` - 文章列表
- `templates/module-cms.php` - CMS 模組
- `templates/module-banners.php` - 輪播圖
- `templates/module-menus.php` - 導航選單
- `templates/module-links.php` - 友情連結
- `templates/module-andb.php` - 廣告位

## 代碼規範

### PHP 代碼規範
- 遵循 WordPress 編碼規範
- 使用函數首碼 `pk_` (Puock)
- 使用常量 `PUOCK` 作為主題名稱
- 所有文字使用國際化: `__('文字', PUOCK)`

### JavaScript 代碼規範
- 使用 ES6+ 語法
- 使用 Babel 轉譯確保相容性
- 代碼風格保持一致

### Less 代碼規範
- 使用變數定義顏色、字體等
- 保持嵌套層級合理
- 注釋清晰

## 修改主題樣式

### 1. 修改主色調
- 編輯 `assets/style/common.less`
- 查找並修改主題色變數
- 執行 `npm run build` 編譯

### 2. 修改佈局
- 編輯對應的 Less 檔
- 支援博客、CMS、企業三種佈局模式

### 3. 自訂樣式
- 推薦在 `assets/style/custom.less` (需建立) 中新增
- 在 `assets/style/common.less` 中引入

## 新增新功能

### 1. 新增新的 Ajax 介面
1. 在 `inc/ajax/` 建立新檔案
2. 在 `inc/ajax/index.php` 中註冊
3. 在前端 JavaScript 中調用

### 2. 新增新的短代碼
1. 在 `inc/fun/short-code.php` 中新增短代碼處理函數
2. 使用 `add_shortcode()` 註冊

### 3. 新增新的頁面範本
1. 在 `pages/` 建立 `template-*.php`
2. 新增範本注釋:
```php
/*
Template Name: 範本名稱
*/
```

### 4. 新增新的設定選項
1. 在 `inc/setting/options/` 建立新的選項類
2. 在 `inc/setting/index.php` 中註冊
3. 使用 `pk_get_option()` 取得選項值

## 常用函數

### 主題選項
```php
pk_get_option($key)           // 取得選項
pk_is_checked($key)           // 檢查是否啟用
```

### 快取函數
```php
pk_cache_get($key)            // 取得快取
pk_cache_set($key, $value)    // 設定快取
```

### Ajax 回應
```php
pk_ajax_resp($data, $msg, $code)      // 成功回應
pk_ajax_resp_error($msg, $data)       // 錯誤回應
```

### 輔助函數
```php
get_post_images($post)       // 取得文章圖片
get_post_category_link()     // 取得分類連結
get_post_tags()              // 取得文章標籤
pk_breadcrumbs()             // 麵包屑導航
pk_get_seo_title()           // SEO 標題
```

## 測試

### 本地開發測試
1. 組態 WordPress 本地環境
2. 啟用主題
3. 執行 `npm run dev` 監聽檔變化
4. 在瀏覽器中測試功能

### 功能測試清單
- [ ] 主題切換（白天/黑夜）
- [ ] 頁面組態切換
- [ ] 登入/註冊
- [ ] 評論功能
- [ ] Ajax 載入
- [ ] SEO 功能
- [ ] 短代碼功能
- [ ] OAuth 登入

## 發佈流程

### 1. 更新版本號
- 編輯 `style.css` 中的版本號
- 編輯 `package.json` 中的版本號

### 2. 構建生產版本
```bash
npm run build
```

### 3. 提交代碼
```bash
git add .
git commit -m "版本更新說明"
git push
```

### 4. 建立 Release
- 在 GitHub 上建立新的 Release
- 上傳主題壓縮包
- 編寫更新日誌

## 調試技巧

### PHP 調試
- 使用 `error_log()` 輸出日誌
- 檢查 WordPress debug 模式
- 查看瀏覽器控制台

### JavaScript 調試
- 使用瀏覽器開發者工具
- 檢查控制台錯誤
- 使用 console.log 調試

### Less 調試
- 使用瀏覽器開發工具檢查樣式
- 使用 Less 變數方便調試

## 常見問題

### Q: 如何修改主題顏色?
A: 編輯 `assets/style/common.less` 中的顏色變數，然後執行 `npm run build`

### Q: 如何禁用某些功能?
A: 在主題設定中關閉對應選項，或在 `functions.php` 中注釋相關代碼

### Q: 如何新增新的圖示?
A: 在 `assets/img/icons/` 新增圖示檔，並在 CSS 中引用

### Q: 構建失敗怎麼辦?
A: 檢查 Node.js 版本，刪除 `node_modules` 和 `package-lock.json`，重新安裝依賴

### Q: 如何自訂範本?
A: 在主題根目錄建立子主題，或直接修改對應範本檔

## 依賴管理

### npm 依賴
主要依賴構建工具:
- gulp
- gulp-babel
- gulp-less
- gulp-uglify
- @babel/core
- @babel/preset-env

### Composer 依賴
- yurunsoft/yurun-oauth-login - OAuth 登入
- zoujingli/ip2region - IP 地址庫
- orhanerday/open-ai - OpenAI 集成
- rahul900day/gpt-3-encoder - GPT-3 編碼器

## 性能優化建議

1. **啟用快取**: 使用主題內置快取功能
2. **壓縮圖片**: 使用 timthumb.php 或其他圖片優化工具
3. **合併檔**: 構建時已自動合併 JS 和 CSS
4. **使用 CDN**: 組態 CDN 加速靜態資源
5. **開啟 Gzip**: 在伺服器組態中開啟 Gzip 壓縮

## 安全建議

1. 定期更新 WordPress 和主題
2. 不要直接修改 `vendor/` 目錄
3. 檢查檔許可權
4. 啟用主題內置的安全功能
5. 定期備份資料庫和檔

## 貢獻指南

1. Fork 專案
2. 建立特性分支 (`git checkout -b feature/AmazingFeature`)
3. 提交更改 (`git commit -m 'Add some AmazingFeature'`)
4. 推送到分支 (`git push origin feature/AmazingFeature`)
5. 建立 Pull Request

## 聯繫方式

- **GitHub**: https://github.com/Licoy/wordpress-theme-puock
- **Issues**: https://github.com/Licoy/wordpress-theme-puock/issues
- **文檔**: https://www.licoy.cn/puock-doc.html

## 許可證

GPL V3.0 - 請遵守開源協定，保留主題底部的署名
