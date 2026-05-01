<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>The Purpose</title>
  <link rel="stylesheet" href="css/display.css">
</head>
<body>
  <div id="app">
    <a href="admin.php" class="settings-link" title="管理ページ">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="3"/>
        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
      </svg>
    </a>

    <div class="items-row" id="items-row">
      <div class="item" id="item-0"></div>
      <div class="item" id="item-1"></div>
      <div class="item" id="item-2"></div>
    </div>

    <div class="goal-area" id="goal-area">
      <div class="goal-content" id="goal-content">
        <label class="goal-label" id="goal-label">
          <input type="checkbox" id="goal-checkbox">
          <span id="goal-text"></span>
        </label>
        <div class="goal-deadline" id="goal-deadline"></div>
      </div>
      <div class="goal-done" id="goal-done" style="display:none">今日の目標完了</div>
      <div class="goal-empty" id="goal-empty" style="display:none">今日の予定が登録されていません</div>
    </div>
  </div>

  <script src="js/display.js"></script>
</body>
</html>
