# fix: Xserver 保存ボタン不動作（data/ ディレクトリ未作成）

## タイムスタンプ
2026-05-04

## 変更内容

- `api/save_day.php`: `file_put_contents` 前に `data/` ディレクトリを `mkdir(0755, true)` で自動生成するよう修正

## 意図

`data/` は `.gitignore` 対象のため FTP デプロイ後に Xserver 上に存在しない。
`file_put_contents` がディレクトリ未作成で PHP Warning を出力し、レスポンスボディに HTML が混入して JSON パースに失敗していた。
初回保存時にディレクトリを自動生成することで解消。
