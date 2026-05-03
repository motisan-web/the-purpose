# 現在の作業状態

> 最終更新: 2026-05-04

---

## 直近で完了したこと

- GitHub Actions デプロイパイプライン構築（→ `.claude-codex/change/deploy-setup.md`）
  - `.github/workflows/deploy.yml` 作成（push/workflow_dispatch トリガー）
  - GitHub Secrets 登録（STAGING_SERVER / STAGING_USERNAME / STAGING_PASSWORD）
  - server-dir を `./` に修正（FTPユーザーのルート = on24.motisan.info の public_html）
  - Fix permissions ステップ追加（644/755 自動修正）
- `.gitignore` に `.ftp-deploy-sync-state.json` を追加
- `new-php-project` スキルを今回の知見で大幅改善

## 次にやること

ユーザーの指示を待つ。

## 注意事項・既知の仕様

- 画面サイズは 1920x1080 固定（モバイル非対応）
- 目標の締切超過 → 自動スキップ・チェック不可（JS setInterval 1分ごと監視）
- `data/` と `config/` は gitignore 対象
- 絶対パス不使用（`dirname(__FILE__)` 起点）
- GitHub: https://github.com/motisan-web/the-purpose.git
- 本番: https://on24.motisan.info/
- デプロイ: push to main → GitHub Actions → FTP → on24.motisan.info
- FTP deploy は `.ftp-deploy-sync-state.json` でキャッシュ管理（gitignore 済み）
- gh CLI は bash PATH 外のため `/c/Program Files/GitHub CLI/gh.exe` でフルパス指定が必要
