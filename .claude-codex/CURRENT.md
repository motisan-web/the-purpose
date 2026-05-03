# 現在の作業状態

> 最終更新: 2026-05-04

---

## 直近で完了したこと

- Xserver 本番環境で保存ボタンが動作しない不具合を修正
  - 原因：`data/` ディレクトリが gitignore 対象のため Xserver 未作成 → `file_put_contents` が PHP Warning を出力してレスポンスに HTML が混入 → JSON パース失敗
  - 修正：`api/save_day.php` に `mkdir($data_dir, 0755, true)` を追加（ディレクトリ自動生成）

## 次にやること

ユーザーの指示を待つ。

## 注意事項・既知の仕様

- 画面サイズは 1920x1080 固定（モバイル非対応）
- 目標の締切超過 → 自動スキップ・チェック不可（JS setInterval 1分ごと監視）
- `data/` と `config/` は gitignore 対象（本番は PHP が初回書き込み時に自動生成）
- 絶対パス不使用（`dirname(__FILE__)` 起点）
- GitHub: https://github.com/motisan-web/the-purpose.git
- 本番: https://on24.motisan.info/
- デプロイ: push to main → GitHub Actions → FTP → on24.motisan.info
- FTP deploy は `.ftp-deploy-sync-state.json` でキャッシュ管理（gitignore 済み）
- gh CLI は bash PATH 外のため `/c/Program Files/GitHub CLI/gh.exe` でフルパス指定が必要
