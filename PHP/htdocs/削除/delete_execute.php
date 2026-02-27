<?php
require_once '../config/csrf_token.php';
// フォームデータを処理する
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    // CSRFトークンの検証
    if (! verify_csrf_token($csrf_token)) {
        // トークンが無効の場合はエラーメッセージを表示して処理を停止
        http_response_code(403);
        echo 'CSRFトークンが無効です。再度お試しください。';
        exit();
    }
}

require '../config/DB_access.php';

// GETでIDを受け取る
$id = $_GET['id'] ?? null;

if (! $id) {
    echo "会員IDが指定されていません。<a href='../会員一覧/member_list.php'>一覧へ戻る</a>";
    exit();
}

try {
    // データベースから削除処理
    // $stmt = $pdo->prepare("DELETE FROM members WHERE member_id = :id");
    // データベースから論理削除処理
    $stmt = $pdo->prepare("UPDATE members SET delete_flg = 1 WHERE member_id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // 削除成功時
        header('Location: ../削除/delete_complete.php');
        exit();
    } else {
        // 該当データが見つからない場合
        echo "指定された会員が存在しません。<a href='../会員一覧/member_list.php'>一覧へ戻る</a>";
        exit();
    }
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
    exit();
}