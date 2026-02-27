<?php
// セッションがまだ開始されていない場合にのみ session_start() を呼び出す
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * CSRFトークンを生成してセッションに保存する関数
 */
function generate_csrf_token()
{
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token; // トークンをセッションに保存
    return $token;
}

/**
 * CSRFトークンを検証する関数
 */
function verify_csrf_token($token)
{
    if (! isset($_SESSION['csrf_token']) || ! hash_equals($_SESSION['csrf_token'], $token)) {
        return false;
    }
    unset($_SESSION['csrf_token']); // 検証後にトークンを削除（使い捨てにする）
    return true;
}
?>
