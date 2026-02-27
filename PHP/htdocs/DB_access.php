<?php
// データベース接続設定
$dsn = 'mysql:host=localhost;dbname=testphp;charset=utf8mb4';
$username = 'root';
$password = 'Masa@19957720abc';

try {
    // ERRMODEを例外として処理。エラー無視を避ける
    // クエリ結果を['column_name' => 'value']の形式で取得
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    echo "データベース接続エラー: " . $e->getMessage();
    exit();
}
?>