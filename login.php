<?php
session_start();

$auth_file    = dirname(__FILE__) . '/config/auth.json';
$attempt_file = dirname(__FILE__) . '/config/login_attempts.json';
$is_first     = !file_exists($auth_file);
$error        = '';
$locked       = false;
$lock_remain  = 0;

define('MAX_ATTEMPTS', 5);
define('LOCK_SECONDS', 900);

function get_attempts($file) {
    if (!file_exists($file)) return ['count' => 0, 'last' => 0];
    return json_decode(file_get_contents($file), true) ?: ['count' => 0, 'last' => 0];
}

function save_attempts($file, $data) {
    file_put_contents($file, json_encode($data));
}

$att = get_attempts($attempt_file);
if ($att['count'] >= MAX_ATTEMPTS) {
    $elapsed = time() - $att['last'];
    if ($elapsed < LOCK_SECONDS) {
        $locked      = true;
        $lock_remain = LOCK_SECONDS - $elapsed;
    } else {
        save_attempts($attempt_file, ['count' => 0, 'last' => 0]);
        $att = ['count' => 0, 'last' => 0];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$locked) {
    $input_id   = trim($_POST['user_id'] ?? '');
    $input_pass = $_POST['password'] ?? '';

    if ($input_id === '' || $input_pass === '') {
        $error = 'IDまたはパスワードが入力されていません';
    } elseif ($is_first) {
        if ($input_id === $input_pass) {
            $hash = password_hash($input_pass, PASSWORD_DEFAULT);
            file_put_contents($auth_file, json_encode([
                'user_id'       => $input_id,
                'password_hash' => $hash,
            ]));
            save_attempts($attempt_file, ['count' => 0, 'last' => 0]);
            $_SESSION['authed'] = true;
            header('Location: admin.php');
            exit;
        } else {
            $error = 'IDまたはパスワードが違います';
        }
    } else {
        $auth = json_decode(file_get_contents($auth_file), true);
        if (
            isset($auth['user_id'], $auth['password_hash']) &&
            $input_id === $auth['user_id'] &&
            password_verify($input_pass, $auth['password_hash'])
        ) {
            save_attempts($attempt_file, ['count' => 0, 'last' => 0]);
            $_SESSION['authed'] = true;
            header('Location: admin.php');
            exit;
        } else {
            $new_count = $att['count'] + 1;
            save_attempts($attempt_file, ['count' => $new_count, 'last' => time()]);
            if ($new_count >= MAX_ATTEMPTS) {
                $locked      = true;
                $lock_remain = LOCK_SECONDS;
            } else {
                $remain = MAX_ATTEMPTS - $new_count;
                $error  = 'IDまたはパスワードが違います（あと' . $remain . '回失敗するとロックされます）';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>ログイン - The Purpose</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html, body {
      min-height: 100vh;
      background: #f5f5f0;
      font-family: 'Helvetica Neue', Arial, 'Hiragino Kaku Gothic ProN', Meiryo, sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .card {
      background: #fff;
      border: 1px solid #e0e0e0;
      border-radius: 14px;
      padding: 48px 56px;
      width: 400px;
    }
    h1 {
      font-size: 20px;
      font-weight: 700;
      color: #1a1a1a;
      margin-bottom: 32px;
      text-align: center;
    }
    label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: #666;
      margin-bottom: 6px;
    }
    input[type="text"], input[type="password"] {
      display: block;
      width: 100%;
      padding: 11px 14px;
      border: 1px solid #ddd;
      border-radius: 7px;
      font-size: 15px;
      background: #fafafa;
      margin-bottom: 18px;
    }
    input:focus { outline: none; border-color: #4a9a6a; background: #fff; }
    .error {
      background: #fff3f3;
      border: 1px solid #f5c0c0;
      border-radius: 7px;
      padding: 10px 14px;
      font-size: 13px;
      color: #c0392b;
      margin-bottom: 18px;
    }
    .locked {
      background: #fff8e1;
      border: 1px solid #ffe082;
      border-radius: 7px;
      padding: 10px 14px;
      font-size: 13px;
      color: #b07800;
      margin-bottom: 18px;
    }
    .hint {
      font-size: 12px;
      color: #aaa;
      text-align: center;
      margin-bottom: 20px;
    }
    button {
      width: 100%;
      background: #4a9a6a;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 13px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
    }
    button:hover { background: #3d8559; }
    button:disabled { background: #ccc; cursor: not-allowed; }
  </style>
</head>
<body>
  <div class="card">
    <h1>管理ページ ログイン</h1>

    <?php if ($locked): ?>
      <div class="locked">
        ログインがロックされています。<?= ceil($lock_remain / 60) ?>分後に再試行してください。
      </div>
    <?php elseif ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($is_first && !$locked): ?>
      <p class="hint">初回設定：IDとパスワードに同じ値を入力してください</p>
    <?php endif; ?>

    <form method="post">
      <label for="user_id">ユーザーID</label>
      <input type="text" id="user_id" name="user_id" autocomplete="username" <?= $locked ? 'disabled' : '' ?>>

      <label for="password">パスワード</label>
      <input type="password" id="password" name="password" autocomplete="current-password" <?= $locked ? 'disabled' : '' ?>>

      <button type="submit" <?= $locked ? 'disabled' : '' ?>>ログイン</button>
    </form>
  </div>
</body>
</html>
