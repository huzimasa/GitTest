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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // 削除対象の type_id を取得
    $type_id = $_GET['type_id'] ?? null;
    
    if ($type_id === null || !ctype_digit($type_id)) {
        header('Location: member_type_detail.php?error=invalid_id');
        exit();
    }
    
    try {
        // 該当する種別が使用されているかを確認
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM members WHERE member_type_id = ?");
        $stmt->execute([$type_id]);
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            // 使用されているため削除できない
            header('Location: member_type_detail.php?error=type_in_use');
            exit();
        }
//論理削除の為コメントアウト         $delete_stmt = $pdo->prepare("DELETE FROM member_types WHERE type_id = ?");

        // 使用されていない場合に削除を実行
        $delete_stmt = $pdo->prepare("UPDATE member_types SET delete_flg = 1 WHERE type_id = :type_id");
        $delete_stmt->execute([':type_id' => $type_id]);
        
        header('Location: member_type_list.php?success=type_deleted');
        exit();
    } catch (PDOException $e) {
        // エラー処理
        error_log("エラー: " . $e->getMessage());
        header('Location: member_type_detail.php?error=delete_failed');
        exit();
    }
}
