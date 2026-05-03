# デプロイ設定（2026-05-04）

## 変更内容

- `.github/workflows/deploy.yml` を新規作成
- GitHub Secrets（STAGING_SERVER / STAGING_USERNAME / STAGING_PASSWORD）を登録
- `.gitignore` に `.ftp-deploy-sync-state.json` を追加
- BASE エントリ（id: 1c26b8）の local_slug / local_dir を更新

## deploy.yml の設定

- 本番URL: on24.motisan.info
- デプロイ方式: FTP（SamKirkland/FTP-Deploy-Action@v4.4.0）
- トリガー: push(main) + workflow_dispatch（両方）
- server-dir: ./（FTPユーザーのルート = on24.motisan.info の public_html）
- パーミッション自動修正: あり（ファイル 644 / ディレクトリ 755）
- 除外: data/, config/, config.local.php, .ftp-deploy-sync-state.json, .git*

## トラブルと解決

- 初回デプロイ後にサイトが表示されなかった → server-dir がフルパス指定で二重ネストになっていた → `./` に修正
- パーミッション 604/705 で 403 Forbidden → Fix permissions ステップを追加して解決
- gh CLI が bash の PATH にない → `/c/Program Files/GitHub CLI/gh.exe` フルパスで対応
- PowerShell では BASE_PASSPHRASE が空になる → bash でのみ使用する方針に変更
