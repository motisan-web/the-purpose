# 現在の作業状態

> 最終更新: 2026-05-01

---

## 直近で完了したこと

- プロジェクト設計確定（目標進行ロジック・データ構造・ファイル構成）
- CLAUDE.md・.claude-codex/ ディレクトリ初期化

## 次にやること

実装フェーズへ。以下の順で進める：
1. `.gitignore` / `.htaccess` / ディレクトリ骨格作成
2. `data/data.json` 初期ファイル
3. API実装（get_day / save_day / toggle_goal / get_all）
4. `index.php` + `css/display.css` + `js/display.js`（表示ページ）
5. `admin.php` + `css/admin.css` + `js/admin.js`（管理ページ）

## 注意事項・既知の仕様

- 画面サイズは 1920x1080 固定（モバイル非対応）
- 目標の締切超過 → 自動スキップ・チェック不可（JS setInterval 1分ごと監視）
- `data/` は gitignore 対象
- 絶対パス不使用（`dirname(__FILE__)` 起点）
- GitHub: https://github.com/motisan-web/the-purpose.git
