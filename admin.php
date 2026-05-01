<?php
session_start();
if (empty($_SESSION['authed'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>管理 - The Purpose</title>
  <link rel="stylesheet" href="css/admin.css">
</head>
<body>
  <div id="app">
    <header>
      <h1>The Purpose — 管理</h1>
      <div class="header-right">
        <a href="index.php" class="back-link">← 表示ページへ</a>
        <a href="api/logout.php" class="logout-link">ログアウト</a>
      </div>
    </header>

    <main>
      <!-- 編集フォーム -->
      <section class="form-section">
        <div class="section-title">
          <label for="edit-date">日付</label>
          <input type="date" id="edit-date">
        </div>

        <div class="items-form">
          <h3>3つのテキスト</h3>
          <input type="text" class="item-input" id="item-0" placeholder="テキスト 1">
          <input type="text" class="item-input" id="item-1" placeholder="テキスト 2">
          <input type="text" class="item-input" id="item-2" placeholder="テキスト 3">
        </div>

        <div class="goals-form">
          <h3>時間付き目標</h3>
          <div id="goals-list"></div>
          <button class="btn-add" id="btn-add-goal">＋ 目標を追加</button>
        </div>

        <div class="form-actions">
          <button class="btn-save" id="btn-save">保存</button>
          <span class="save-msg" id="save-msg"></span>
        </div>
      </section>

      <!-- 過去一覧 -->
      <section class="history-section">
        <h2>過去の記録</h2>
        <div id="history-list"></div>
      </section>
    </main>
  </div>

  <script src="js/admin.js"></script>
</body>
</html>
